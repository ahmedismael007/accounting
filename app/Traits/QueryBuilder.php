<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait QueryBuilder
{
    public function applyQuery(Request $request, $query)
    {
        $search = $request->input('search');
        $filters = $request->input('filters', []);
        $sortBy = $request->input('sort_by', 'id');
        $sortDir = $request->input('sort_dir', 'desc');
        $perPage = $request->input('per_page', 100);

        // Get only allowed columns (fillable)
        $model = $query->getModel();
        $allowedColumns = array_merge($model->getFillable(), ['created_at']);

        // 🔍 Search any column
        if ($search) {
            $query->where(function ($q) use ($search, $allowedColumns) {
                foreach ($allowedColumns as $column) {
                    $q->orWhere($column, 'like', "%$search%");
                }
            });
        }

        // 🧮 Filtering
        foreach ($filters as $column => $condition) {
            if (!in_array($column, $allowedColumns)) {
                abort(422, "Filtering by column [$column] is not allowed.");
            }

            if (is_null($condition)) {
                $query->whereNull($column);
                continue;
            }

            if ($condition === '!null') {
                $query->whereNotNull($column);
                continue;
            }

            if (!is_array($condition)) {
                $query->where($column, $condition);
                continue;
            }

            foreach ($condition as $operator => $value) {
                switch ($operator) {
                    case '=':
                    case '!=':
                    case '>':
                    case '>=':
                    case '<':
                    case '<=':
                        $query->where($column, $operator, $value);
                        break;

                    case 'like':
                        $query->where($column, 'LIKE', $value);
                        break;

                    case 'in':
                        if (!is_array($value)) {
                            abort(422, "The 'in' operator expects an array of values.");
                        }
                        $query->whereIn($column, $value);
                        break;

                    case 'not_in':
                        if (!is_array($value)) {
                            abort(422, "The 'not_in' operator expects an array of values.");
                        }
                        $query->whereNotIn($column, $value);
                        break;

                    case 'between':
                        if (!is_array($value) || count($value) !== 2) {
                            abort(422, "The 'between' operator expects an array of exactly 2 values.");
                        }
                        $query->whereBetween($column, $value);
                        break;

                    case 'not_between':
                        if (!is_array($value) || count($value) !== 2) {
                            abort(422, "The 'not_between' operator expects an array of exactly 2 values.");
                        }
                        $query->whereNotBetween($column, $value);
                        break;

                    default:
                        abort(422, "Unsupported operator [$operator] for column [$column].");
                }
            }
        }


        // 📊 Sorting
        $sortBy = in_array($sortBy, ['id', 'created_at']) ? 'created_at' : $sortBy;

        if (!in_array($sortBy, $allowedColumns)) {
            abort(422, "Sorting by column [$sortBy] is not allowed.");
        }

        $query->orderBy($sortBy, $sortDir);

        // 📄 Paginate
        return $query->paginate($perPage);
    }
}
