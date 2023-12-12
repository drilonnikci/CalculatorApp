<?php

namespace App\Http\Controllers;

use App\Helpers\MathExpressionParser;
use App\Models\Results;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        // Validate the request
        $request->validate([
            'expression' => 'required|string',
        ]);
        $mathExpression = new MathExpressionParser();

        try {
            // Sanitize user input
            $sanitizedExpression = htmlspecialchars($request->input('expression'));
            // Evaluate the expression
            $expression = $mathExpression->calculate($sanitizedExpression)[0];

            $result = Results::create([
                'expression_result' => $expression['expressionResult'],
            ]);

            return response()->json(['result' => $expression['result']]);
        } catch (\Throwable $e) {
            // Handle errors, such as division by zero, syntax errors, etc.
            return response()->json(['result' => 'Calculation error!']);
        }
    }


}
