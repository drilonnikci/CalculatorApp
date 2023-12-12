<?php

namespace App\Helpers;

class MathExpressionParser
{
    /** @var array */
    private array $operators = ['+', '-', 'x', '÷', '%'];

    /**
     * Calculate the result of a mathematical expression.
     *
     * @param string $expression The mathematical expression to calculate.
     *
     * @return array An array containing the result and an error message (if any).
     */
    public function calculate(string $expression): array
    {
        $errorMessage = '';
        $validatedExpression = $this->validateExpression($expression);

        try {
            $tokens = $this->tokenize($validatedExpression);
            $result = $this->parse($tokens, $validatedExpression);
        } catch (\Exception $e) {
            $result = null;
            $errorMessage = $e->getMessage();
        }
        return [$result, $errorMessage];
    }

    /**
     * Validates and normalizes the mathematical expression.
     *
     * @param string $expression The input mathematical expression.
     *
     * @return string The normalized expression.
     */
    private function validateExpression(string $expression): string
    {
        $lastChar = substr($expression, -1);
        $isOperatorLastCharacter = preg_match('/[+\-x÷]/', $lastChar);

        // Remove last operator if not needed to avoid errors
        $expression = $isOperatorLastCharacter ? substr($expression, 0, -1) : $expression;

        // Iterate through each character in the expression
        $normalizedExpression = '';
        $length = strlen($expression);

        for ($i = 0; $i < $length; $i++) {
            $currentChar = $expression[$i];

            // If the current character is an opening parenthesis and the previous character is a digit, add 'x' before the opening parenthesis
            if ($currentChar == '(' && $i > 0 && is_numeric($expression[$i - 1])) {
                $normalizedExpression .= 'x' . $currentChar;
            }
            // If the current character is a closing parenthesis and the next character is a digit, add 'x' after the closing parenthesis
            elseif ($currentChar == ')' && $i < $length - 1 && is_numeric($expression[$i + 1])) {
                $normalizedExpression .= $currentChar . 'x';
            }
            // Otherwise, just add the current character as is
            else {
                $normalizedExpression .= $currentChar;
            }
        }

        return $normalizedExpression;
    }

    /**
     * Tokenizes the mathematical expression into numbers and operators.
     *
     * @param string $expression The normalized mathematical expression.
     *
     * @return array Associative array with a 'numbers' key containing the tokenized numbers.
     */
    private function tokenize(string $expression): array
    {
        $stackedNumbers = [];
        $stackedOperators = [];

        $pattern = "/\d+|\+|\-|\*|\/|x|÷|\(|\)/";
        preg_match_all($pattern, $expression, $matches);
        $tokens = array_merge(...$matches);

        foreach ($tokens as $token) {
            if (is_numeric($token)) {
                $stackedNumbers[] = $token;
            } elseif (in_array($token, $this->operators)) {
                while (!empty($stackedOperators) && $this->getPrecedence(end($stackedOperators)) >= $this->getPrecedence($token)) {
                    $stackedNumbers[] = array_pop($stackedOperators);
                }
                $stackedOperators[] = $token;
            } elseif ($token === '(') {
                $stackedOperators[] = $token;
            } elseif ($token === ')') {
                while (!empty($stackedOperators) && end($stackedOperators) !== '(') {
                    $stackedNumbers[] = array_pop($stackedOperators);
                }
                array_pop($stackedOperators);
            }
        }

        while (!empty($stackedOperators)) {
            $stackedNumbers[] = array_pop($stackedOperators);
        }


        return ['numbers' => $stackedNumbers];
    }

    /**
     * Parses the tokenized expression and calculates the result.
     *
     * @param array $tokens Associative array with a 'numbers' key containing tokenized numbers.
     *
     * @return array The calculated result.
     * @throws \Exception If the expression is invalid.
     */
    private function parse(array $tokens, string $expression): array
    {
        $numbers = $tokens['numbers'];
        $stack = [];

        foreach ($numbers as $number) {
            if (is_numeric($number)) {
                $stack[] = $number;
            } elseif (in_array($number, $this->operators)) {
                if (count($stack) < 2) {
                    throw new \Exception("Invalid expression");
                }
                $operand2 = array_pop($stack);
                $operand1 = array_pop($stack);
                $result = $this->applyOperation($number, $operand1, $operand2);
                $stack[] = $result;
            }
        }

        if (count($stack) !== 1) {
            throw new \Exception("Invalid expression");
        }

        $result = $expression . "=" . $stack[0];

        return ['result' => $stack[0], 'expressionResult' => $result];
    }

    /**
     * Applies the mathematical operation based on the operator.
     *
     * @param string $operator The mathematical operator.
     * @param mixed  $operand1 The first operand.
     * @param mixed  $operand2 The second operand.
     *
     * @return mixed The result of the operation.
     * @throws \Exception If the operator is invalid.
     */
    private function applyOperation(string $operator, mixed $operand1, mixed $operand2): mixed
    {
        return match ($operator) {
            '+' => $operand1 + $operand2,
            '-' => $operand1 - $operand2,
            'x' => $operand1 * $operand2,
            '÷' => $operand2 != 0 ? $operand1 / $operand2 : 0,
            default => throw new \Exception("Invalid operator: $operator"),
        };
    }

    /**
     * Returns the precedence of the operator for correct parsing order.
     *
     * @param string $operator The mathematical operator.
     *
     * @return int The precedence of the operator.
     */
    private function getPrecedence(string $operator): int
    {
        return match ($operator) {
            '+', '-' => 1,
            'x', '÷' => 2,
            default => 0,
        };
    }
}
