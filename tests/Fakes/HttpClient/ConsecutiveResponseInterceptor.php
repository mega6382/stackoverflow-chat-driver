<?php declare(strict_types=1);

namespace AsyncBot\Driver\StackOverflowChatTest\Fakes\HttpClient;

use Amp\CancellationToken;
use Amp\Http\Client\ApplicationInterceptor;
use Amp\Http\Client\DelegateHttpClient;
use Amp\Http\Client\Request;
use Amp\Http\Client\Response;
use Amp\Promise;

final class ConsecutiveResponseInterceptor implements ApplicationInterceptor
{
    private array $interceptors;

    public function __construct(ApplicationInterceptor ...$interceptors)
    {
        $this->interceptors = $interceptors;
    }

    /**
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     *
     * @return Promise<Response>
     */
    public function request(Request $request, CancellationToken $cancellation, DelegateHttpClient $client): Promise
    {
        if (!count($this->interceptors)) {
            throw new \RuntimeException('No more interceptors left');
        }

        /** @var ApplicationInterceptor $interceptor */
        $interceptor = array_shift($this->interceptors);

        return $interceptor->request($request, $cancellation, $client);
    }
}
