<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class PetController extends Controller
{
    private const BASE_LINK = 'https://petstore.swagger.io/v2';

    private function getInividualPet(int $id) : array
    {
        $response = Http::get(self::BASE_LINK . sprintf('/pet/%s', $id));
        if ($response->getStatusCode() == 404) {
            abort(404);
        }
        return $response->json();
    }

    private function existsInividualPet(int $id) : bool
    {
        $response = Http::get(self::BASE_LINK . sprintf('/pet/%s', $id));
        if ($response->getStatusCode() == 404) {
            abort(404, 'Page not found');
        }
        if ($response->getStatusCode() == 200) {
            return true;
        }
        return false;
    }
    
    public function findPetById(int $id): View
    {
        $data = $this->getInividualPet($id);
        return view('menu.show', ['item' => $data]);
    }

    public function updatePetForm(int $id): View
    {
        $data = $this->getInividualPet($id);
        return view('menu.update', ['item' => $data]);
    }

    private function getRules(): array
    {
        return [
            'id'       => 'required|numeric',
            'category_name'    => 'required|string',
            'category_id' => 'required|numeric',
            'pet_name' => 'required|string',
            'status'  => 'required|string'
        ];
    }

    private function createDynamicRules(array $formData): array
    {
        $rules = $this->getRules();
        foreach (array_keys($formData) as $key) {
            // Sprawdzamy, czy nazwa pola zaczyna się od 'id.'
            if (preg_match('/^id_(\d+)$/', $key, $matches)) {    
                    $id = $matches[1];
                    $rules['id_' . $id] = 'required|integer'; 
                    $rules['name_' . $id] = 'required|string|max:255';
            } 
        } 
        return $rules;
    }

    private function createJsonBody(array $validatedData): array
    {
        $tags = [];
        foreach (array_keys($validatedData) as $key) {
            if (preg_match('/^id_(\d+)$/', $key, $matches)) {    
                $id = $matches[1];
                $tags[] = ['id' => $validatedData['id_' . $id], 'name' => $validatedData['name_' . $id]];
            }
        }

        return [
            'id' => $validatedData['id'],
            'name' => $validatedData['pet_name'],
            'category' => [
                'id' => $validatedData['category_id'],
                'name' => $validatedData['category_name']
            ],
            'photoUrls' => [
                'string'
            ],
            'tags' => $tags,
            'status' => $validatedData['status'] 
        ];
    }

    private function handleErrorUpdate(int $num): void
    {
        if ($num == 400) {
            abort(400, 'Invalid ID supplied');
        }
        if ($num == 404) {
            abort(404, 'Pet not found');
        }
        if ($num == 405) {
            abort(404, 'Validation exception');
        }
    }

    public function updatePetAction(Request $request, int $id)
    {
        $this->getInividualPet($id);
        $rules = $this->createDynamicRules($request->all());
        $validatedData = $request->validate($rules);
        $jsonBody = $this->createJsonBody($validatedData);
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->PUT(self::BASE_LINK . '/pet', $jsonBody);

        if ($response->getStatusCode() !== 200) {
            $this->handleErrorUpdate($response->getStatusCode());
        }

        if ($response->getStatusCode() == 200) {
            return Redirect::route('pet.get', ['id' => $id]);
        }
    }

    public function addPetForm()
    {
        return view('menu.add');
    }

    public function addPetAction(Request $request)
    {
        $rules = $this->createDynamicRules($request->all());
        $validatedData = $request->validate($rules);
        echo var_dump($validatedData['id']);
        return;
    }
} 
