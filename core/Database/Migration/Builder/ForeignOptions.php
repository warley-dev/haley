<?php

namespace Haley\Database\Migration\Builder;

class ForeignOptions
{
    /**
     * CASCADE - SET NULL - SET DEFAULT - RESTRICT - NO ACTION
     */
    public function onDelete(string $value = 'NO ACTION')
    {
        $key = array_key_last(BuilderMemory::$foreigns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$foreigns[$key]['on_delete'] = ' ON DELETE ' . $value;
        }

        return $this;
    }

    /**
     * CASCADE - SET NULL - SET DEFAULT - RESTRICT -NO ACTION
     */
    public function onUpdate(string $value = 'NO ACTION')
    {
        $key = array_key_last(BuilderMemory::$foreigns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$foreigns[$key]['on_update'] = ' ON UPDATE ' . $value;
        }

        return $this;
    }

    public function name(string $value)
    {
        $key = array_key_last(BuilderMemory::$foreigns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$foreigns[$key]['name'] = $value;
        }

        return $this;
    }
}
