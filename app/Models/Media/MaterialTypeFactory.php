<?php


namespace App\Models\Media;

use App\Common\Interfaces\Query\DefaultQueryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class MaterialTypeFactory
 */
final class MaterialTypeFactory
{
    public const TYPES_BOOK = ['BK', 'VM', 'MX'];
    public const TYPES_DISC = ['CF', 'MP', 'MU'];
    public const TYPES_JOURNAL = ['CR'];

    /**
     * @param string $typeKey
     * @return DefaultQueryInterface
     */
    public static function getMaterialClass(string $typeKey): DefaultQueryInterface
    {
        if (in_array($typeKey, self::TYPES_BOOK)) {
            return new Book();
        }

        if (in_array($typeKey, self::TYPES_DISC)) {
            return new Disc();
        }

        if (in_array($typeKey, self::TYPES_JOURNAL)) {
            return new Journal();
        }
    }

    public static function getMaterialQuery(string $typeKey, int $id): Builder
    {
        if (in_array($typeKey, self::TYPES_BOOK)) {
            return DB::table('lib_books')->where('book_id', $id);
        }

        if (in_array($typeKey, self::TYPES_DISC)) {
            return DB::table('lib_discs')->where('disc_id', $id);
        }

        if (in_array($typeKey, self::TYPES_JOURNAL)) {
            return DB::table('lib_journals')->where('journal_id', $id);
        }
    }
}
