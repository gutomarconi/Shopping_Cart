<?php

class ShoppingCart
{
    protected $cartId;
    private $items = [];

    public function __construct()
    {
        $this->cartId = md5('ShoppingCart') . '_cart';
        $this->read();
    }

    public function getItems()
    {
        return $this->items;
    }

    public function isEmpty()
    {
        return empty(array_filter($this->items));
    }

    public function getTotalItem()
    {
        $total = 0;
        foreach ($this->items as $items) {
            foreach ($items as $item) {
                    ++$total;
            }
        }
        return $total;
    }

    public function clear()
    {
        $this->items = [];
        $this->write();
    }

    public function add($id)
    {
        $quantity = 1;

        if (isset($this->items[$id])) {
            foreach ($this->items[$id] as $index => $item) {
                $this->items[$id][$index]['quantity'] += $quantity;
                $this->write();
                return true;
            }
        }
        $this->items[$id][] = [
            'id'         => $id,
            'quantity'   => $quantity,
        ];
        $this->write();
        return true;
    }
	
    public function remove($id)
    {
        if (!isset($this->items[$id])) {
            return false;
        }
        foreach ($this->items[$id] as $index => $item) {
            unset($this->items[$id][$index]);
            $this->write();
            return true;
        }
        return false;
    }

    public function destroy()
    {
        $this->items = [];
        setcookie($this->cartId, '', -1);
    }

    private function read()
    {
        $this->items = (json_decode((isset($_COOKIE[$this->cartId])) ? $_COOKIE[$this->cartId] : '[]', true));
    }

    private function write()
    {
        setcookie($this->cartId, json_encode(array_filter($this->items)), time() + 604800);
    }
}
