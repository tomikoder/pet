<?php

#declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class PetController extends Controller
{
    private const BASE_LINK = 'https://petstore.swagger.io/v2';
    private const STATUSES  = ['available', 'pending', 'sold'];

    private function prepareHttpRequest(bool $addContentType)
    {
        $headers = [
            'accept' => 'application/json',
        ];

        if ($addContentType) {
            $headers['Content-Type'] = 'application/json';
        }
        return Http::withHeaders($headers);   
    }

    private function handleErrorPet(int $errorCode): void
    {
        if ($errorCode == 400) {
            abort(400, "Invalid ID supplied");
        }

        if ($errorCode == 404) {
            abort(404, 'Pet not found');
        }
    }

    private function getInividualPet(int $id)
    {
        $response = $this->prepareHttpRequest(false)->GET(self::BASE_LINK . sprintf('/pet/%s', $id));
        if ($response->getStatusCode() != 200) {
            $this->handleErrorPet($response->getStatusCode());
        }
        return $response->json();
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

    public function updatePetAction(Request $request, int $id)
    {
        $rules = $this->createDynamicRules($request->all());
        $validatedData = $request->validate($rules);
        $jsonBody = $this->createJsonBody($validatedData);
        $response = $this->prepareHttpRequest(true)->PUT(self::BASE_LINK . '/pet', $jsonBody);
        if ($response->getStatusCode() !== 200) {
            $this->handleErrorPet($response->getStatusCode());
        }

        return Redirect::route('pet.get', ['id' => $id]);
    }

    public function addPetForm()
    {
        return view('menu.add');
    }

    public function addPetAction(Request $request)
    {
        $rules = $this->createDynamicRules($request->all());
        $validatedData = $request->validate($rules);
        $id = $validatedData['id'];
        $jsonBody = $this->createJsonBody($validatedData);
        $this->prepareHttpRequest(true)->POST(self::BASE_LINK . '/pet', $jsonBody);
        return Redirect::route('pet.get', ['id' => $id]);
    }

    public function deletePetForm()
    {
        return view('menu.delete');
    }

    public function deletePetAction(Request $request)
    {
        $validatedData = $request->validate(['id' => 'required|numeric']);
        $id = $validatedData['id'];
        $response = $this->prepareHttpRequest(true)->DELETE(self::BASE_LINK . sprintf('/pet/%s', $id));
        if ($response->getStatusCode() !== 200) {
            $this->handleErrorPet($response->getStatusCode());
        }
        return response('Success!');      
    }

    public function searchByStatusForm()
    {
        return view('menu.findByStatus', ['statuses' => self::STATUSES]);   
    }

    public function searchByStatusAction(Request $request)
    {
        $request->validate([
            'status' => ['required', 'array'], 
            'status.*' => ['in:' . implode(',', self::STATUSES)]
        ]);
        $statuses = $request->input('status');
        $statusesWithPrefix = array_map(fn($status) => "status={$status}", $statuses);
        $queryString = implode('&', $statusesWithPrefix);
        $data = $this->prepareHttpRequest(false)->GET(self::BASE_LINK . '/pet/findByStatus?' . $queryString)->json();
        return view('menu.list', ['pets' => $data]);   
    }

    public function searchByTagsForm()
    {
        return view('menu.findByTags');   
    }

    public function searchByStatusTags(Request $request)
    {
        $tags = $request->input('tags');
        $tagsWithPrefix = array_map(fn($tag) => "tags={$tag}", $tags);
        $queryString = implode('&', $tagsWithPrefix);
        $data = $this->prepareHttpRequest(false)->GET(self::BASE_LINK . '/pet/findByTags?' . $queryString)->json();
        return view('menu.list', ['pets' => $data]);   
    }
} 
