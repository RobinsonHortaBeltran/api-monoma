<?php

namespace Tests\Unit;

use App\Models\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCandidate()
    {
        // Preparar
        $candidateData = [
            'name' => 'John Doe',
            'source' => 'fotocasa',
            'owner' => '1',
            'created_by'=>'1'
        ];

        // Ejecutar
        $candidate = Candidate::create($candidateData);

        // Verificar
        $this->assertInstanceOf(Candidate::class, $candidate);
        $this->assertEquals('John Doe', $candidate->name);
        
    }

    public function testUpdateCandidate()
    {
        // Preparar
        $candidate = Candidate::factory()->create();

        // Ejecutar
        $candidate->source = 'fotocasa';
        $candidate->save();

        // Verificar
        $this->assertEquals('fotocasa', $candidate->source);
    }

    public function testDeleteCandidate()
    {
        // Preparar
        $candidate = Candidate::factory()->create();

        // Ejecutar
        $candidate->delete();

        // Verificar
        $this->assertDatabaseMissing('candidates', ['id' => $candidate->id]);
    }
}