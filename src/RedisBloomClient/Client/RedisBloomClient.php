<?php
/**
 * @project   phpredis-bloom
 * @author    Rafael Campoy <rafa.campoy@gmail.com>
 * @copyright 2019 Rafael Campoy <rafa.campoy@gmail.com>
 * @license   MIT
 * @link      https://github.com/averias/php-rejson
 *
 * Copyright and license information, is included in
 * the LICENSE file that is distributed with this source code.
 */

namespace Averias\RedisBloom\Client;

use Averias\RedisBloom\Command\Traits\BloomCommandTrait;
use Averias\RedisBloom\Exception\ResponseException;
use Averias\RedisBloom\Adapter\RedisClientAdapterInterface;
use Averias\RedisBloom\Parser\ParserTrait;
use Averias\RedisBloom\Validator\InputValidatorTrait;

class RedisBloomClient implements RedisBloomClientInterface
{
    use BloomCommandTrait;
    use InputValidatorTrait;
    use ParserTrait;

    /** @var RedisClientAdapterInterface */
    protected $redisClientAdapter;

    public function __construct(RedisClientAdapterInterface $redisClientAdapter)
    {
        $this->redisClientAdapter = $redisClientAdapter;
    }

    /**
     * @param string $commandName
     * @param array $arguments
     * @return mixed
     * @throws ResponseException
     */
    public function executeRawCommand(string $commandName, ...$arguments)
    {
        return $this->redisClientAdapter->executeRawCommand($commandName, ...$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws ResponseException
     */
    public function __call(string $name, array $arguments)
    {
        return $this->redisClientAdapter->executeCommandByName($name, $arguments);
    }

    /**
     * @param string $command
     * @param string $key
     * @param array $params
     * @return mixed
     * @throws ResponseException
     */
    protected function executeBloomCommand(string $command, string $key, array $params = [])
    {
        $response = $this->redisClientAdapter->executeBloomCommand($command, $key, $params);
        return $this->parseResponse($command, $response);
    }
}
