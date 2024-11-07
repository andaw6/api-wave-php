<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

use function PHPUnit\Framework\isEmpty;

class SenderResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $content = $response->getData(true);
        } else {
            $content = json_decode($response->getContent(), true) ?? $response->getContent();
        }


        if (isset($content["exception"])) {
            return response()->json([
                "data" => null,
                "status" => "ECHEC",
                "message" => $content["message"]
            ], 404);
        }
        $statusCode = $response->getStatusCode();
        // Si le contenu est null, renvoyer une réponse vide avec le statut 404
        if (is_null($content)) {
            $responseData = [
                'data' => null,
                'status' => "ECHEC",
                'message' => 'Ressource non trouvée'
            ];
            return response()->json($responseData, 404);
        }

        $statusCode = $statusCode > 0 ? $statusCode : 200;
        $pagination = null;
        // Gérer les cas où le contenu est un tableau associatif ou une simple liste
        if (is_array($content)) {
            $data = $content['data'] ?? $content;
            $pagination = isset($content["pagination"]) ? $content["pagination"] : null;
            $statusCode = isset($content['status']) ? (int) $content['status'] : $statusCode;
            $message = $content['message'] ?? ($statusCode === 200 ? 'Ressource trouvée' : 'Ressource non trouvée');

            // Si le contenu est une liste simple (non associatif)
            if (array_keys($content) === range(0, count($content) - 1)) {
                $data = $content;
            }
        } else {
            // Si le contenu n'est pas un tableau (ex. une simple chaîne ou autre)
            $data = $content;
            $message = (int)$statusCode === 200 ? 'Ressource trouvée' : 'Ressource non trouvée';
        }

        $status = ($statusCode == 200 || $statusCode == 201) ? "SUCCESS" : "ECHEC";
        // Préparer la structure de la réponse
        $responseData = [
            'data' => $data,
            'status' => $status,
            'message' => $message,
        ];
        if ($pagination)
            $responseData["pagination"] = $pagination;

        return response()->json($responseData)->setStatusCode($statusCode > 0 ? $statusCode : 200);
    }
}
