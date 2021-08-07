<?php

namespace IsaEken\Picpurify\Traits;

use JetBrains\PhpStorm\Pure;

trait HasTasks
{
    /**
     * @return array
     */
    #[Pure] public function getTasks(): array
    {
        return $this->getAttributes()['tasks'];
    }

    /**
     * @param array $tasks
     * @return $this
     */
    public function setTasks(array $tasks): static
    {
        $attributes = $this->getAttributes();
        $attributes['tasks'] = $tasks;
        $this->setAttributes($attributes);
        return $this;
    }

    /**
     * @param string $task
     * @return bool
     */
    public function hasTask(string $task): bool
    {
        return array_search(mb_strtolower($task), $this->getTasks()) !== false;
    }

    /**
     * @param string $task
     * @return $this
     */
    public function addTask(string $task): static
    {
        if (! $this->hasTask($task = mb_strtolower($task))) {
            $this->getTasks()[] = $task;
        }

        return $this;
    }

    /**
     * @param string $task
     * @return $this
     */
    public function removeTask(string $task): static
    {
        if ($this->hasTask($task = mb_strtolower($task))) {
            $tasks = $this->getTasks();
            unset($tasks[array_search($task, $tasks)]);
            return $this->setTasks($tasks);
        }

        return $this;
    }

    /**
     * @return string
     */
    #[Pure] public function parseTasks(): string
    {
        return implode(',', array_unique($this->getTasks()));
    }
}
