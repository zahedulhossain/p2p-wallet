<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, string>|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'status' => $this->resource->status,
            'from_wallet_id' => $this->resource->from_wallet_id,
            'to_wallet_id' => $this->resource->to_wallet_id,
            'amount' => $this->resource->amount,
            'conversion_rate' => $this->resource->conversion_rate,
            'converted_amount' => $this->resource->converted_amount,
            'note' => $this->resource->note,
        ];
    }
}
