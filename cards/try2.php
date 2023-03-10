<?php

class Validator
{
    public static function validate(string $cardNumber)
    {
        // удалим ненужное из номера
        // $cardNumber=preg_replace('/ /', '', $cardNumber);
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        // Алгоритм Луна
        if (!self::isValidLuhn($cardNumber)) {
            return 'невалидная :(';
        }

        // Виза или Мастеркард?
        $emitent = self::getEmitent($cardNumber);

        // возврат валидации и эмитента
        return 'валидная, ' . $emitent;
    }

    private static function isValidLuhn(string $cardNumber): bool
    {
        $sum = 0;
        $alt = false;
        for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
            $num = intval($cardNumber[$i]);
            if ($alt) {
                $num *= 2;
                if ($num > 9) {
                    $num = $num % 10 + 1;
                }
            }
            $sum += $num;
            $alt = !$alt;
        }
        return $sum % 10 == 0;
    }

    private static function getEmitent(string $cardNumber)
    {
        // виза
        if (preg_match('/^(4[0-9]{1}|14)/', $cardNumber)) {
            return 'Visa';
        }

        // мастеркард
        if (preg_match('/^(5[1-5]{1}|62|67)/', $cardNumber)) {
            return 'Mastercard';
        }

        // эмитент не определен
        return 'название эмитента не определено';
    }
}

$cardNumber = $argv[1];
$result = Validator::validate($cardNumber);
echo $result;

// php try2.php 4750657776370372 - валидная, VISA
// php try2.php 4023 9019 5678 9014 - валидная, VISA
// php try2.php 5569191777864116 - валидная, MasterCard
// php try2.php 725163728819929 - невалидная