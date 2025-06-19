<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'category' => $this->category,
            'description' => $this->description,
            'status' => $this->status,
            'image' => $this->image ? asset('image/books/' . $this->image) : null, // Assuming images are stored in public/image/books/
        ];
    }
}
