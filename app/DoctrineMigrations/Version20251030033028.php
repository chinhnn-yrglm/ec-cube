<?php

declare(strict_types=1);

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251030033028 extends AbstractMigration
{
    public const PAGE_TABLE_NAME = 'dtb_page';
    public const PAGE_LAYOUT_TABLE_NAME = 'dtb_page_layout';
    public const INQUIRY_PAGES = [
        [
            'id' => 49,
            'page_name' => 'Inquiry',
            'url' => 'inquiry',
            'file_name' => 'Inquiry/index',
            'edit_type' => 2,
            'create_date' => 'CURRENT_TIMESTAMP',
            'update_date' => 'CURRENT_TIMESTAMP',
            'discriminator_type' => 'page',
        ],
        [
            'id' => 50,
            'page_name' => 'Inquiry Confirmation',
            'url' => 'inquiry_confirm',
            'file_name' => 'Inquiry/confirm',
            'edit_type' => 3,
            'create_date' => 'CURRENT_TIMESTAMP',
            'update_date' => 'CURRENT_TIMESTAMP',
            'discriminator_type' => 'page',
        ],
        [
            'id' => 51,
            'page_name' => 'Inquiry Complete',
            'url' => 'inquiry_complete',
            'file_name' => 'Inquiry/complete',
            'edit_type' => 2,
            'create_date' => 'CURRENT_TIMESTAMP',
            'update_date' => 'CURRENT_TIMESTAMP',
            'discriminator_type' => 'page',
        ],
    ];
    public const INQUIRY_PAGE_LAYOUTS = [
        [
            'page_id' => 49,
            'layout_id' => 2,
            'sort_no' => 45,
            'discriminator_type' => 'pagelayout',
        ],
        [
            'page_id' => 50,
            'layout_id' => 2,
            'sort_no' => 46,
            'discriminator_type' => 'pagelayout',
        ],
        [
            'page_id' => 51,
            'layout_id' => 2,
            'sort_no' => 47,
            'discriminator_type' => 'pagelayout',
        ],
    ];

    public function up(Schema $schema): void
    {
        // Check table exists
        if (!$schema->hasTable(self::PAGE_TABLE_NAME)) {
            return;
        }

        $pageExits = $this->connection->fetchOne('SELECT COUNT(*) FROM ' . self::PAGE_TABLE_NAME . " WHERE url IN ('inquiry', 'inquiry_confirm', 'inquiry_complete')");
        if ($pageExits == 0) {
            // Insert inquiry pages
            foreach (self::INQUIRY_PAGES as $page) {
                $sql = $this->buildInsertSql(self::PAGE_TABLE_NAME, $page);
                $this->addSql($sql);
            }

            // Insert inquiry page layouts
            foreach (self::INQUIRY_PAGE_LAYOUTS as $pageLayout) {
                $sql = $this->buildInsertSql(self::PAGE_LAYOUT_TABLE_NAME, $pageLayout);
                $this->addSql($sql);
            }
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM ' . self::PAGE_LAYOUT_TABLE_NAME . ' WHERE page_id IN (49, 50, 51)');
        $this->addSql('DELETE FROM ' . self::PAGE_TABLE_NAME . " WHERE url IN ('inquiry', 'inquiry_confirm', 'inquiry_complete')");
    }

    private function buildInsertSql(string $tableName, array $data): string
    {
        $connection = $this->connection;
        // Extract columns and values
        $columns = implode(', ', array_keys($data));

        // Quote values
        $quotedValues = array_map(function ($value) use ($connection) {
            return !in_array($value, ['NULL', 'CURRENT_TIMESTAMP']) ? $connection->quote($value) : $value;
        }, array_values($data));

        $values = implode(', ', $quotedValues);

        // Return the final SQL
        return sprintf('INSERT INTO %s (%s) VALUES (%s)', $tableName, $columns, $values);
    }
}
