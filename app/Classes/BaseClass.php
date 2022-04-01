<?php

namespace App\Classes;

use App\Interfaces\ClassInterface;
use App\Models\BaseModel;
use App\Types\DBWhereParamsType;
use CodeIgniter\Model;

class BaseClass implements ClassInterface
{
    public BaseModel $Model;

    public function findByPrimary(int|string $primary, DBWhereParamsType $DBWhereParamsType = null)
    {
        $model = $this->Model;

        if ($DBWhereParamsType != null) {
            $model->select($DBWhereParamsType->select);

            if ($DBWhereParamsType->ignore_deleted) {
                $model->withDeleted();
            }
        }


        $result = $model
            ->findAction($primary);

        if ($result == null) {
            return null;
        } else {
            return $result;
        }
    }

    public function findOne(DBWhereParamsType $DBWhereParamsType)
    {
        $model = $this->Model;

        $model->select($DBWhereParamsType->select);

        $this->setWhere($model, $DBWhereParamsType);

        $result = $model
            ->findAction();

        if ($result == null) {
            return null;
        } else {
            return $result[0];
        }
    }

    public function findAll(DBWhereParamsType $DBWhereParamsType): array|null
    {
        $model = $this->Model;

        $model->select($DBWhereParamsType->select);

        $this->setWhere($model, $DBWhereParamsType);

        $model = $model->orderBy($DBWhereParamsType->sort_row, $DBWhereParamsType->sort_direction);
        $model = $model->limit($DBWhereParamsType->limit, $DBWhereParamsType->offset);

        return $model->findAllAction();
    }

    public function delete(int|string $primary, string $row_name = ''): void
    {
        if ($row_name == '') {
            $this->Model
                ->where([$this->Model->getPrimaryKey() => $primary])
                ->delete();
        } else {
            $this->Model
                ->where([$row_name => $primary])
                ->delete();
        }
    }

    public function deleteWhere(DBWhereParamsType $DBWhereParamsType): void
    {
        $model = $this->Model;

        $this->setWhere($model, $DBWhereParamsType);

        $model
            ->delete();
    }

    public function countAll(): int
    {
        return $this->Model->countAll();
    }

    public function countWhere(DBWhereParamsType $DBWhereParamsType): int
    {
        $model = $this->Model;

        $model->select($DBWhereParamsType->select);

        $this->setWhere($model, $DBWhereParamsType);

        $model = $model->limit($DBWhereParamsType->limit, $DBWhereParamsType->offset);

        return $model->countAllResults();
    }


    private function setWhere(Model &$model, DBWhereParamsType $DBWhereParamsType)
    {
        if ($DBWhereParamsType->ignore_deleted) {
            $model->withDeleted();
        }

        foreach ($DBWhereParamsType->where as $key => $param) {
            if (is_array($param)) {
                foreach ($param as $inparam) {
                    $model = $model->orWhere($key, $inparam);
                }
            } else {
                $model = $model->where($key, $param);
            }
        }
    }

    public function edit(array $payload): bool
    {
        delete_timestamps($payload);

        if (!isset($payload[$this->Model->getPrimaryKey()])) {
            throw new \Exception('Edit must have primary (' . $this->Model->getPrimaryKey() . ')');
        }

        try {
            $this->Model->update($payload[$this->Model->getPrimaryKey()], $payload);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function create(array $payload): int|bool
    {
        delete_timestamps($payload);

        try {
            return $this->Model->insert($payload);
        } catch (\Exception $e) {
            return false;
        }
    }
}