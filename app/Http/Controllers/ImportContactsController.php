<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportContactsController
{
    private const BUFFER_SIZE = 500;

    public function create()
    {
        return view('import.create');
    }

    public function edit(string $import)
    {
        $file = new \SplFileObject(\storage_path('app/imports/' . $import));
        $file->setFlags(\SplFileObject::READ_CSV);

        $columns = (new Contact())->getFillable();

        $headers = [];
        $records = [];

        foreach ($file as $index => $record) {
            if ($index === 0) {
                $headers = $record;
            } elseif ($index < 6) {
                $records[] = $record;
            } else {
                break;
            }
        }

        return view('import.edit', [
            'import' => $import,
            'columns' => $columns,
            'headers' => $headers,
            'records' => $records,
        ]);
    }

    public function store(Request $request)
    {
        $import = Str::uuid()->toString();

        $request->file('file')->storeAs('imports', $import);

        return redirect()->route('import.edit', ['import' => $import]);
    }

    public function update(Request $request, string $import)
    {
        $file = new \SplFileObject(\storage_path('app/imports/' . $import));
        $file->setFlags(\SplFileObject::READ_CSV);

        $map = $request->input('map', []);
        $header = \array_keys($map);

        $buffer = [];
        $attributes = [];
        $now = \now()->toDateTimeString();

        foreach ($file as $index => $record) {
            if ($index === 0) {
                continue; // skip headers
            }

            if (\blank($record) || (\count($record) === 1 && \blank($record[0]))) {
                continue; // skip empty rows
            }

            $record = \array_combine($header, $record);

            $attributes = [];

            foreach ($map as $csvColumn => $dbColumn) {
                if ($dbColumn) {
                    $attributes[$dbColumn] = $record[$csvColumn] ?? null;
                }
            }

            $attributes['created_at'] = $now;
            $attributes['updated_at'] = $now;

            $buffer[] = \array_values($attributes);

            if (\count($buffer) === self::BUFFER_SIZE) {
                $this->flush($buffer, \array_keys($attributes));
                $buffer = [];
            }
        }

        if (\count($buffer) > 0) {
            $this->flush($buffer, \array_keys($attributes));
        }

        Storage::delete('imports/' . $import);

        return redirect()->route('contacts.index', [
            'time' => \microtime(true) - LARAVEL_START,
        ]);
    }

    public function destroy(string $import)
    {
        Storage::delete('imports/' . $import);

        return redirect()->route('contacts.index');
    }

    private function flush(array $buffer, array $columns)
    {
        static $count = 0;
        static $statement = null;

        if (! $statement || $count !== \count($buffer)) {
            $count = \count($buffer);
            $statement = $this->prepare($columns, $count);
        }

        $statement->execute(\array_merge([], ...$buffer));
    }

    private function prepare(array $columns, int $count)
    {
        $model = new Contact();

        $query = 'INSERT INTO ' . $model->getTable();
        $query .= '(' . \implode(',', $model->qualifyColumns($columns)) . ')';
        $query .= ' VALUES ';
        $query .= \implode(
            ',',
            \array_fill(0, $count, '(' . \implode(',', \array_fill(0, \count($columns), '?')) . ')'),
        );

        return $model->getConnection()->getPdo()->prepare($query);
    }
}
