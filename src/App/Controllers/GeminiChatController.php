<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Paw\App\Models\User;
use Paw\App\Models\GeminiChat;
use Paw\App\Repositories\GeminiChatRepository;
use Twig\Environment;
use GuzzleHttp\Client;
require __DIR__ . '/../../../vendor/autoload.php';
use Dotenv\Dotenv;

class GeminiChatController extends Controller
{

    private $geminiChatRepository;

    public function __construct(Environment $twig) {
        parent::__construct($twig);
        $this->geminiChatRepository = GeminiChatRepository::getInstance();
    }

    public function sendMessageGemini(Request $request) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (!$request->user()) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Invalid JSON']);
            exit;
        }

        if (!isset($data["type_msg"]) || !isset($data["message"])) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing params type_msg or message']);
            exit;
        }

        if (!($data["type_msg"] === 1 || $data["type_msg"] === 2)){
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Param type_msg invalid values']);
            exit;
        }

        $message= "";

        if ($data["type_msg"] === 1){
            $message = "Quiero un/a " . $data["message"][0] . " para un PC " . $data["message"][1];
        }
        if ($data["type_msg"] === 2){
            $message = "Quiero una PC " . $data["message"][0] . ", ¿qué configuración me recomiendas?";
        } 

        # Extraigo del .env la clave de gemini
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 3));
        $dotenv->load();
        $apiKey = $_ENV['API_KEY_GEMINI']; 

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey";

        $client = new Client([
            'verify' => false, // Deshabilita la verificación SSL
        ]);

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $message]
                            ]
                        ]
                    ]
                ]
            ]);

            // Obtener el cuerpo de la respuesta
            $body = $response->getBody();
            $dataGemini = json_decode($body, true);
        }
        catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e]);
            exit;
        }

        // Guardo el mensaje del usuario
        $text = $message;
        $user = $request->user();
        $gemini_msj = false;
        $dataRepository = [
            "text" => $text, 
            "user" => $user, 
            "gemini_msj" => $gemini_msj
        ];
        
        $this->geminiChatRepository->create($dataRepository);

        // Guardo el mensaje de gemini
        $text = $dataGemini['candidates'][0]['content']['parts'][0]['text'];
        $gemini_msj = true;
        $dataRepository = [
            "text" => $text, 
            "user" => $user, 
            "gemini_msj" => $gemini_msj
        ];
        $this->geminiChatRepository->create($dataRepository);

        http_response_code(200);
        echo json_encode(["success" => true, "gemini_text" => $text, "user_text" => $message]);
    }

    public function getAllMessages(Request $request){
        if (!$request->user()) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        $user = $request->user();

        $geminiChat = $this->geminiChatRepository->getByUser($user);

        if (!$geminiChat){
            http_response_code(200);
            echo json_encode(["success" => true, "messages" => null]);
        }

        $chatArrays = [];
        foreach ($geminiChat as $message) {
            $messageArray = $message->toArray();
            // Elimina el objeto "user" por seguridad
            unset($messageArray['user']);
            $chatArrays[] = $messageArray;
        }

        http_response_code(200);
        echo json_encode(["success" => true, "messages" => $chatArrays]);
    }
}