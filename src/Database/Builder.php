<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Database;

class Builder extends \Illuminate\Database\Eloquent\Builder
{
    /**
     * @return false|int|mixed
     *
     * @psalm-suppress RedundantPropertyInitializationCheck
     */
    public function delete()
    {
        if (isset($this->onDelete)) {
            return call_user_func($this->onDelete, $this);
        }

        return $this->toBase()->delete();
    }

    public function update(array $values)
    {
        return $this->toBase()->update($this->addUpdatedAtColumn($values));
    }
}
