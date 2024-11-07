<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;

class ExceptionHandlerMiddleware
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
        try {
            return $next($request);
        } catch (ModelNotFoundException $e) {
            return $this->handleException($e, 404, 'Ressource non trouvée');
        } catch (RepositoryException $e) {
            return $this->handleException($e, 500, 'Erreur dans le repository');
        } catch (ServiceException $e) {
            return $this->handleException($e, 500, 'Erreur dans le service');
        } catch (\Exception $e) {
            return $this->handleException($e, 500, 'Erreur interne du serveur');
        }
    }

    /**
     * Handle the exception and return a JsonResponse.
     *
     * @param  \Exception  $e
     * @param  int  $statusCode
     * @param  string  $defaultMessage
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleException(\Exception $e, int $statusCode, string $defaultMessage): JsonResponse
    {
        $message = $e->getMessage() ?: $defaultMessage;
        $responseData = [
            'data' => null,
            'status' => 'ECHEC',
            'message' => $message,
        ];

        return response()->json($responseData, $statusCode);
    }
}