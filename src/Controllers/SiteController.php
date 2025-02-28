<?php

namespace Src\Controllers;

use Pimple\Container;
use Src\Attributes\Route;
use Src\Database;
use Src\Session;

class SiteController extends Controller
{
    public function processPrompt()
    {
        // Get the prompt input from the request
        $prompt = $this->get('prompt');


        // Prepare data to send to the Hugging Face API
        $data = json_encode([
            'inputs' => "Only give me the SQL query to execute without any extra content or explanation for the following text: " . $prompt .
                ". the table strcutre is,  users(first_name, last_name, age, email, phone, gender['female', 'male'), address."
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . '-------------------------------',
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        // Check for curl errors
        if (curl_errno($ch)) {
        }

        curl_close($ch);

        $responseData = json_decode($response, true);
        $generatedQuery = trim($responseData[0]['generated_text'] ?? '');

        // $regex = '/```sql\s*(SELECT.*?;)\s*```/is';
        // $regex = '/```(?:sql\s*)?(SELECT.*?;)\s*```/is';
        echo $generatedQuery;


        // if (preg_match($regex, $generatedQuery, $matches)) {
        //     $sqlQuery = $matches[1]; // Extracted SQL query
        //     // echo $sqlQuery;
        //     var_dump($this->db()->query($sqlQuery));
        // } else {
        //     echo "No SQL query found.";
        // }

        // $regex = '/```(?:sql\s*)?(SELECT.*?;)\s*```/is';
        $regex = '/```(?:sql\s*)?(SELECT.*?FROM\s+.*?)(WHERE[\s\S]*?)(?=```|$)/i';
        // $regex = '/```(?:sql\s*)?(SELECT.*?FROM\s+.*?)(WHERE.*?)(?=```)/is';

        if (preg_match($regex, $generatedQuery, $matches)) {
            $sqlQuery = $matches[1]; // Extracted SQL query
            // $sqlQuery = $matches[1] . $matches[2]; // Extracted SQL query


            // Execute the query
            $results = $this->db()->query($sqlQuery);

            // Check if results exist and render HTML
            if (!empty($results)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<thead>";
                echo "<tr>";

                // Generate table headers dynamically
                $firstRow = is_object($results[0]) ? (array)$results[0] : $results[0];
                foreach (array_keys($firstRow) as $header) {
                    echo "<th style='padding: 8px; text-align: left;'>" . htmlspecialchars($header) . "</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                // Generate table rows dynamically
                foreach ($results as $row) {
                    // Convert stdClass to an array if necessary
                    $rowArray = is_object($row) ? (array)$row : $row;

                    echo "<tr>";
                    foreach ($rowArray as $cell) {
                        echo "<td style='padding: 8px;'>" . htmlspecialchars($cell) . "</td>";
                    }
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "No results found.";
            }
        } else {
            echo "No SQL query found.";
        }
    }

    #[Route('GET', '/')]
    public function home()
    {
        $pageTitle = 'Home';
        $user      = user();

        $this->render('home', compact('pageTitle', 'user'));
    }

    #[Route('GET', '/about')]
    public function about()
    {
        $pageTitle = 'About';

        $this->render('About', compact('pageTitle'));
    }

    #[Route('GET', '/contact')]
    public function contact()
    {
        $pageTitle = 'Contact';

        $this->render('contact', compact('pageTitle'));
    }

    #[Route('POST', '/contact')]
    public function submitContact()
    {
        $name    = $this->post('name', 'required');
        $email   = $this->post('email', 'required|email');
        $message = $this->post('message', 'required|min:3');

        $errors = $this->validation();

        if ($errors) {
            Session::flash('error', $this->renderErrors());
            redirect('/contact');
        }

        $contactId = $this->db()->insert('contacts', [
            'name'    => $name,
            'email'   => $email,
            'message' => $message
        ]);

        Session::flash('success', 'Contact created');
        Session::clearOldValues();

        redirect('/');
    }
}
