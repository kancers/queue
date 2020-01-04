<?php
declare(strict_types=1);

namespace Queue\Mailer;

use Cake\Mailer\Exception\MissingActionException;
use Queue\Job\MailerJob;
use Queue\QueueManager;

/**
 * Provides functionality for queuing actions from mailer classes.
 */
trait QueueTrait
{
    /**
     * Pushes a mailer action onto the queue.
     *
     * @param string $action The name of the mailer action to trigger.
     * @param array $args Arguments to pass to the triggered mailer action.
     * @param array $headers Headers to set.
     * @param array $options an array of options for publishing the job
     * @return void
     * @throws \Cake\Mailer\Exception\MissingActionException
     */
    protected function push(string $action, array $args = [], array $headers = [], array $options = []): void
    {
        if (!method_exists($this, $action)) {
            throw new MissingActionException([
                'mailer' => $this->getName() . 'Mailer',
                'action' => $action,
            ]);
        }

        QueueManager::push([MailerJob::class, 'dispatchAction'], [
            'mailerName' => self::class,
            'action' => $action,
            'args' => $args,
            'headers' => $headers,
        ], $options);
    }
}