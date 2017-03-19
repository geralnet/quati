<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model {
    public function addQuantity(int $quantity) {
        if ($quantity < 0) {
            throw new \InvalidArgumentException('Invalid quantity.');
        }

        $this->quantity += $quantity;
        $this->save();
    }

    public function save(array $options = []) {
        // Only save if associated with an user.
        if (!is_null($this->user_id)) {
            parent::save($options);
        }
    }

    public function setQuantity(int $quantity) {
        if ($quantity < 0) {
            throw new \InvalidArgumentException('Invalid quantity.');
        }

        $this->quantity = $quantity;

        if ($this->quantity == 0) {
            $this->delete();
        }
        else {
            $this->save();
        }
    }
}
