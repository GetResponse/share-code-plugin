<?php
namespace ShareCode\Cart;

/**
 * Class CartService
 * @package ShareCode\Cart
 */
class CartService
{
    /**
     * @param CartCommand $command
     * @return string
     */
    public function sendCart(CartCommand $command)
    {
        $grCart = [];

        return $grCart['cartId'];
    }
}
