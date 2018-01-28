<?php

class Purchase {

    private $id;
    private $product_id;
    private $user_id;
    private $amount;
    private $total_price;

    /**
     * @param PDO $conn
     * @return bool|Exception|PDOException
     */
    public function transaction(\PDO $conn) {
        if ($this->user_id && $this->product_id && $this->amount) {
            try {
                $conn->beginTransaction();
                if (!$this->getId()) {
                    $product = Product::loadById($conn, $this->product_id);
                    if ($product->deductAmount($conn,$this->amount)) {
                        $total_price = $product->getPrice() * $this->getAmount();
                        $stmt = $conn->prepare(
                            'INSERT INTO Purchase (product_id, user_id, amount, total_price) VALUES (:product_id, :user_id, :amount, :total_price)');
                        $stmt->execute([
                            'product_id' => $this->getProductId(),
                            'user_id' => $this->getUserId(),
                            'amount' => $this->getAmount(),
                            'total_price' => ($total_price)
                        ]);
                        $this->total_price = $total_price;
                        $this->id = $conn->lastInsertId();
                        $conn->commit();
                        return true;
                    };
                }
                return false;
            } catch (\PDOException $exception) {
                $conn->rollBack();
                return $exception;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Purchase
     */
    private function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param mixed $product_id
     * @return Purchase
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return Purchase
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->total_price;
    }

    /**
     * @param $total_price
     * @return $this
     */
    public function setTotalPrice($total_price)
    {
        $this->total_price = $total_price;
        return $this;
    }





}