<?php


class Product {

    private $id;
    private $name;
    private $price;
    private $amount;

    /**
     * @param PDO $conn
     * @param $id
     * @return null|Product
     */
    static function loadById(\PDO $conn, $id) {
        $stmt = $conn->prepare('SELECT * FROM Product WHERE id=:id');
        $res = $stmt->execute(['id' => $id]);
        if ($res && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $product = new Product();
            $product
                ->setId($row['id'])
                ->setName($row['name'])
                ->setPrice($row['price'])
                ->setAmount($row['amount']);
            return $product;
        } else {
            return null;
        }
    }

    /**
     * @param PDO $conn
     * @param $name
     * @return array
     */
    static function loadByName(\PDO $conn, $name) {
        $stmt = $conn->prepare('SELECT * FROM Product WHERE name=:name');
        $stmt->execute(['name' => $name]);
        $res = array();
        foreach ($stmt->fetchAll() as $row) {
            $product = new Product();
            $product
                ->setId($row['id'])
                ->setName($row['name'])
                ->setPrice($row['price'])
                ->setAmount($row['amount']);
            $res[] = $product;
        }
        return $res;
    }

    /**
     * @param PDO $conn
     * @return array
     */
    static public function loadAll(\PDO $conn) {
        $stmt = $conn->query('SELECT * FROM Product');
        $res = array();
        foreach ($stmt->fetchAll() as $row) {
            $product = new Product();
            $product
                ->setId($row['id'])
                ->setName($row['name'])
                ->setPrice($row['price'])
                ->setAmount($row['amount']);
            $res[] = $product;
        }
        return $res;
    }

    /**
     * @param PDO $conn
     * @return bool
     */
    public function createNew(\PDO $conn) {
        if (!$this->getId()) {
            $stmt = $conn->prepare('INSERT INTO Product (name, price, amount) VALUES (:name, :price, :amount)');
            $res = $stmt->execute([
                'name' => $this->getName(),
                'price' => $this->getPrice(),
                'amount' => $this->getAmount()
            ]);
            if ($res !== false ) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        }
        return false;
    }

    /**
     * @param PDO $conn
     * @param $amount
     * @return bool
     */
    public function addAmount(\PDO $conn, $amount) {
        if ($this->getId() && $amount > 0) {
            $stmt = $conn->prepare(
                'UPDATE Product SET amount=amount+:amount WHERE id=:id'
            );
            $res = $stmt->execute([
                'amount' => $amount,
                'id' => $this->getId()
            ]);
            return (bool) $res;
        }
        return false;
    }

    /**
     * @param PDO $conn
     * @param $amount
     * @return bool
     */
    public function deductAmount(\PDO $conn, $amount) {
        if ($this->getId() && $amount > 0) {
            $stmt = $conn->prepare(
                'UPDATE Product SET amount=amount-:amount WHERE id=:id AND (amount-:amount >= 0)'
            );
            $stmt->execute([
                'amount' => $amount,
                'id' => $this->getId()
            ]);
            return (bool) $stmt->rowCount();
        }
        return false;
    }

    /**
     * @param PDO $conn
     * @return bool
     */
    public function deleteById(\PDO $conn) {
        if ($this->id) {
            $stmt = $conn->prepare('DELETE FROM Product WHERE id=:id');
            $stmt->execute([
                'id'=>$this->id
            ]);
            return (bool) $stmt->rowCount();
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
     * @return Product
     */
    private function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
     * @param mixed $amount
     * @return Product
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

}