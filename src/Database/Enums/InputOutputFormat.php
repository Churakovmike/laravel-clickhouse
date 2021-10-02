<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Database\Enums;

class InputOutputFormat
{
    public const TAB_SEPARATED = 'TabSeparated';
    public const TAB_SEPARATED_RAW = 'TabSeparatedRaw';
    public const TAB_SEPARATED_WITH_NAMES = 'TabSeparatedWithNames';
    public const TAB_SEPARATED_WITH_NAMES_AND_TYPES = 'TabSeparatedWithNamesAndTypes';
    public const TEMPLATE = 'Template';
    public const TEMPLATE_IGNORE_SPACES = 'TemplateIgnoreSpaces';
    public const CSV = 'CSV';
    public const CSV_WITH_NAMES = 'CSVWithNames';
    public const CUSTOM_SEPARATED = 'CustomSeparated';
    public const VALUES = 'Values';
    public const Vertical = 'Vertical';
    public const VERTICAL_RAW = 'VerticalRaw';
    public const JSON = 'JSON';
    public const JSON_AS_STRING = 'JSONAsString';
    public const JSON_STRING = 'JSONString';
    public const JSON_COMPACT = 'JSONCompact';
    public const JSON_COMPACT_STRING = 'JSONCompactString';
    public const JSON_EACH_ROW = 'JSONEachRow';
    public const JSON_EACH_ROW_WITH_PROGRESS = 'JSONEachRowWithProgress';
    public const JSON_STRING_EACH_ROW = 'JSONStringEachRow';
    public const JSON_STRING_EACH_ROW_WITH_PROGRESS = 'JSONStringEachRowWithProgress';
    public const JSON_COMPACT_EACH_ROW = 'JSONCompactEachRow';
    public const JSON_COMPACT_EACH_ROW_WITH_NAMES_AND_TYPES = 'JSONCompactEachRowWithNamesAndTypes';
    public const JSON_COMPACT_STRING_EACH_ROW = 'JSONCompactStringEachRow';
    public const JSON_COMPACT_STRING_EACH_ROW_WITH_NAMES_AND_TYPES = 'JSONCompactStringEachRowWithNamesAndTypes';
    public const TSKV = 'TSKV';
    public const PRETTY = 'Pretty';
    public const PRETTY_COMPACT = 'PrettyCompact';
    public const PRETTY_COMPACT_MONO_BLOCK = 'PrettyCompactMonoBlock';
    public const PRETTY_NO_ESCAPES = 'PrettyNoEscapes';
    public const PRETTY_SPACE = 'PrettySpace';
    public const PROTOBUF = 'Protobuf';
    public const PROTOBUF_SINGLE = 'ProtobufSingle';
    public const AVRO = 'Avro';
    public const AVRO_CONFLUENT = 'AvroConfluent';
    public const PARQUET = 'Parquet';
    public const ARROW = 'Arrow';
    public const ARROW_STREAM = 'ArrowStream';
    public const ORC = 'ORC';
    public const ROW_BINARY = 'RowBinary';
    public const ROW_BINARY_WITH_NAMES_AND_TYPES = 'RowBinaryWithNamesAndTypes';
    public const NATIVE = 'Native';
    public const NULL = 'Null';
    public const XML = 'XML';
    public const CAPN_PROTO = 'CapnProto';
    public const LINE_AS_STRING = 'LineAsString';
    public const REGEXP = 'Regexp';
    public const RAW_BLOB = 'RawBLOB';

    public function cases(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        return $reflectionClass->getConstants();
    }
}
