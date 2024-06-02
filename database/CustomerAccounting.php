<?php

class CustomerAccounting {
    private PDO $pdo;
    public string $customer_id;
    public function __construct(PDO $pdo, $customer_id) {
        $this->pdo = $pdo;
        $this->customer_id = $customer_id;
    }

    /**
     * Returns the tax level for a statement
     * @param $statement_id
     * @return float
     */
    public function getTax($statement_id) : float {
        $sql = 'SELECT tax FROM financial_statements WHERE $customer_id = ? AND id = ? LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([$this->customer_id, $statement_id]);
        if(!$res) return 0;
        return $stmt->fetchColumn();
    }

    /**
     * Get Tax Amount for a statement, do not call when adding a new account
     * Call separately
     * @param $statement_id
     * @return float
     */
    public function getTaxAmount($statement_id) : float {
        $sql = 'SELECT tax, net_product AS net FROM financial_statements WHERE customer_id = ? AND id = ? LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([$this->customer_id, $statement_id]);
        if(!$res) return 0;
        $arr = $stmt->fetchAll();
        $net = $arr['net_product'];
        $tax = $arr['tax'];
        return $tax > 0 ? $net * $tax : $net;
    }

    /**
     * Returns the discount level for a statement
     * @param $statement_id
     * @return float
     */
    public function getDiscount($statement_id) : float {
        $sql = 'SELECT discount FROM financial_statements WHERE customer_id = ? AND id = ? LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([$this->customer_id, $statement_id]);
        if(!$res) return 0;
        return $stmt->fetchColumn();
    }

    /**
     * Calculate the discount amount from the percentage
     * @param $statement_id
     * @return float
     */
    public function getDiscountAmount($statement_id) : float {
        $sql = 'SELECT net_product AS net, discount from financial_statements WHERE customer_id = ? AND id = ? LIMIT 1';
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([$this->customer_id, $statement_id]);
        if(!$res) return 0;
        $arr = $stmt->fetchAll();
        $arr = reset($arr);
        $net = $arr['net'];
        $discount = $arr['discount'];
        return $discount > 0 ? $net * $discount : $net;
    }

    /**
     * Returns the numbers of financial statements opened with some basic data
     * Returns array with attributes id, date, name and status
     * @return array|null
     */
    function getFinancialStatementsCount() : ?array {
        $sql = 'SELECT A.id AS statement_id, DATE(A.start_date) AS date, CONCAT(B.name, " ", B.lastname) AS name, A.status AS status FROM financial_statements A JOIN customers_info B ON A.customer_id = B.id WHERE customer_id = ? ORDER BY date DESC';
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->customer_id]);
        return $stmt->fetchAll();
    }

    /**
     * Returns the list of statements related to a single business
     * @param $statement_id
     * @return array|null
     */
    function getSingleFinancialStatement($statement_id) : ?array {
        $sql = 'SELECT id as statement_id, business_id, start_date AS start, end_date AS end, delivery_date AS delivery, location_addr as rescue_address, advance_payment AS advance, total_payment AS payment, discount, total_expense AS expenses, net_product AS net, gross_income AS gross, status FROM financial_statements WHERE customer_id = ? AND id = ? LIMIT 1';
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->customer_id, $statement_id]);
        $statements = $stmt->fetchAll();
        $statements = reset($statements);
        return $statements ?? null;
    }

    function getFinancialStatements($customer_id) : ?array {
        $sql = 'SELECT * from financial_statements WHERE customer_id = ? ORDER BY start_date DESC';
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$customer_id]);
        $statements = $stmt->fetchAll();
        return $statements;
    }

    /**
     * Returns an array with the lists of works done
     * @param $statement_id
     * @return array|null
     */
    function getFinancialAccounts($statement_id) : ?array {
        $sql = 'SELECT * FROM financial_accounts WHERE customer_id = ? AND statement_id = ? ORDER BY date';
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->customer_id, $statement_id]);
        $accounts = $stmt->fetchAll();
        if(!empty($accounts)) return $accounts;
        return null;
    }

    function updateFinancialStatement($statement_id) : ?array {
        $sql = 'SELECT parts_expense, service_revenue, sub_total FROM financial_accounts WHERE business_id = ? AND statement_id = ?';
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->business_id, $statement_id]);
        $accounts = $stmt->fetchAll();
        if(empty($accounts)) return $accounts;
        $arr = array('total_expense' => 0, 'service_revenue' => 0, 'sub_total' => 0);
        foreach($accounts as $account) {
            $arr['total_expense'] += $account['parts_expense'];
            $arr['service_revenue'] += $account['service_revenue'];
            $arr['sub_total'] += $account['sub_total'];
        }
        $arr['discount'] = $this->getDiscount($statement_id);
        $arr['discount_amount'] = $this->getDiscountAmount($statement_id);
        $arr['tax'] = $this->getTax($statement_id);
        $arr['tax_amount'] = $arr['sub_total'] * $arr['tax'];
        $arr['gross_amount'] = $arr['sub_total'] + $arr['tax_amount'];
        $stmt->closeCursor();
        $sql = 'SELECT advance_payment, total_payment from financial_statements WHERE id = ? AND business_id = ? LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([$statement_id, $this->business_id]);
        if(!$res) return null;
        $res = $stmt->fetchAll();
        $res = reset($res);
        $arr['advance_payment']  = $res['advance_payment'];
        $arr['total_payment']  = $res['total_payment'];
        $arr['gross_amount'] -= $arr['total_payment'];
        $sql = 'UPDATE financial_statements SET total_expense = ?, net_product = ?, gross_income = ? WHERE id = ? and business_id = ?';
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([$arr['total_expense'], $arr['sub_total'], $arr['gross_amount'], $statement_id, $this->business_id]);
        if($res) return $arr;
        return null;
    }

    function interruptRepairs($statement_id) : bool {
        $this->pdo->beginTransaction();
        $sql = 'UPDATE financial_statements SET status = "ABORT", end_date = NOW() WHERE id = ? AND customer_id = ?';
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([$statement_id, $this->customer_id]);
        $sql = 'SELECT business_id from financial_statements WHERE id = ? AND customer_id = ? LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $res &= $stmt->execute([$statement_id, $this->customer_id]);
        $business_id = $stmt->fetchColumn();
        $sql = 'INSERT INTO financial_accounts(statement_id, date, customer_id, business_id, vehicle_service_desc) VALUES(?, NOW(), ?, ?, "END")';
        $stmt = $this->pdo->prepare($sql);
        $res &= $stmt->execute([$statement_id, $this->customer_id, $business_id]);
        if($res) {
            $this->pdo->commit();
        }
        return $res;
    }

    /**
     * Update advance payment
     * @param $statement_id
     * @param float $amount
     * @return bool
     */
    function payNowAdvance($statement_id, float $amount): bool {
        $sql = 'UPDATE financial_statements SET advance_payment = advance_payment + ?, total_payment = total_payment + ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$amount, $amount, $statement_id]);
    }

    /**
     * Update new payment
     * @param $statement_id
     * @param float $amount
     * @return bool
     */
    function payNowEnd($statement_id, float $amount) : bool {
        $sql = 'UPDATE financial_statements SET total_payment = total_payment + ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$amount, $statement_id]);
    }
}
