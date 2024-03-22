<?php

namespace Zoe\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;

class CacheResponse
{
    protected $replacementString = '<zoe-responsecache-csrf-token-here>';
    public const RESPONSE_TYPE_NORMAL = 'response_type_normal';
    public const RESPONSE_TYPE_FILE = 'response_type_file';

    public function prepareResponseToCache(Response $response): string
    {
        if (!$response->getContent()) {
            return "";
        }
        $html = str_replace(
            csrf_token(),
            $this->replacementString,
            $response->getContent()
        );
        $response->setContent($html."<!-- Cache ".date("Y-m-d H:i:s")." --!>");
        return $html;
    }

    public function replaceInCachedResponse(Response $response): string
    {
        if (!$response->getContent()) {
            return "";
        }
        $html = str_replace(
            $this->replacementString,
            csrf_token(),
            $response->getContent()
        );
        $html = preg_replace('#\<div class="_csrf_input"\>(.+?)\<\/div\>#s', '<input cache="true" type="hidden" name="_token" value="' . csrf_token() . '">', $html);
        $response->setContent($html);
        return $html;
    }

    public function handle($request, Closure $next, ...$args)
    {
        if ($request->ajax()) {
            return $next($request);
        } else {
            if (Cache::has($args[0])) {
                $response = $this->unserialize(Cache::get($args[0]));
                $this->replaceInCachedResponse($response);
                return $response;
            } else {
                $response = $next($request);
                $this->prepareResponseToCache($response);
                Cache::add($args[0], $this->serialize($response), $args[1]);
                $this->replaceInCachedResponse($response);
                return $response;

            }
        }
    }

    public function terminate($request, $response)
    {

    }

    public function serialize(Response $response): string
    {
        return serialize($this->getResponseData($response));
    }

    public function unserialize(string $serializedResponse): Response
    {
        $responseProperties = unserialize($serializedResponse);
        if (!$this->containsValidResponseProperties($responseProperties)) {
            throw new  Exception("Could not unserialize serialized response `{$serializedResponse}`");
        }
        $response = $this->buildResponse($responseProperties);
        $response->headers = $responseProperties['headers'];
        return $response;
    }

    protected function getResponseData(Response $response): array
    {
        $statusCode = $response->getStatusCode();
        $headers = $response->headers;
        if ($response instanceof BinaryFileResponse) {
            $content = $response->getFile()->getPathname();
            $type = self::RESPONSE_TYPE_FILE;
            return compact('statusCode', 'headers', 'content', 'type');
        }
        $content = $response->getContent();
        $type = self::RESPONSE_TYPE_NORMAL;
        return compact('statusCode', 'headers', 'content', 'type');
    }

    protected function containsValidResponseProperties($properties): bool
    {
        if (!is_array($properties)) {
            return false;
        }
        if (!isset($properties['content'], $properties['statusCode'])) {
            return false;
        }
        return true;
    }

    protected function buildResponse(array $responseProperties): Response
    {
        $type = $responseProperties['type'] ?? self::RESPONSE_TYPE_NORMAL;
        if ($type === self::RESPONSE_TYPE_FILE) {
            return new BinaryFileResponse(
                $responseProperties['content'],
                $responseProperties['statusCode']
            );
        }
        return new IlluminateResponse($responseProperties['content'], $responseProperties['statusCode']);
    }
}