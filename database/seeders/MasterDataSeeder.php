<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aminoacido;
use App\Models\Mineral;
use App\Models\Vitamina;
use App\Models\Especie;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        $this->seedEspecies();
        $this->seedAminoacidos();
        $this->seedMinerales();
        $this->seedVitaminas();
    }
    
    private function seedEspecies()
    {
        $especies = [
            ['nombre' => 'Cerdo', 'codigo' => 'CER', 'activo' => true],
            ['nombre' => 'Bovino', 'codigo' => 'BOV', 'activo' => true],
            ['nombre' => 'Ovino', 'codigo' => 'OVI', 'activo' => true],
            ['nombre' => 'Caprino', 'codigo' => 'CAP', 'activo' => true],
            ['nombre' => 'Ave', 'codigo' => 'AVE', 'activo' => true],
            ['nombre' => 'Ratón', 'codigo' => 'RAT', 'activo' => true],
            ['nombre' => 'Conejo', 'codigo' => 'CON', 'activo' => true],
            ['nombre' => 'Caballo', 'codigo' => 'CAB', 'activo' => true],
            ['nombre' => 'Perro', 'codigo' => 'PER', 'activo' => true],
            ['nombre' => 'Gato', 'codigo' => 'GAT', 'activo' => true],
        ];
        
        foreach ($especies as $especie) {
            Especie::firstOrCreate(
                ['nombre' => $especie['nombre']],
                $especie
            );
        }
    }
    
    private function seedAminoacidos()
    {
        $aminoacidos = [
            ['nombre' => 'Arginina', 'abreviatura' => 'Arg', 'esencial' => true, 'orden' => 1],
            ['nombre' => 'Glicina', 'abreviatura' => 'Gly', 'esencial' => false, 'orden' => 2],
            ['nombre' => 'Histidina', 'abreviatura' => 'His', 'esencial' => true, 'orden' => 3],
            ['nombre' => 'Isoleucina', 'abreviatura' => 'Ile', 'esencial' => true, 'orden' => 4],
            ['nombre' => 'Leucina', 'abreviatura' => 'Leu', 'esencial' => true, 'orden' => 5],
            ['nombre' => 'Lisina', 'abreviatura' => 'Lys', 'esencial' => true, 'orden' => 6],
            ['nombre' => 'Metionina', 'abreviatura' => 'Met', 'esencial' => true, 'orden' => 7],
            ['nombre' => 'Cistina', 'abreviatura' => 'Cys', 'esencial' => false, 'orden' => 8],
            ['nombre' => 'Fenilalanina', 'abreviatura' => 'Phe', 'esencial' => true, 'orden' => 9],
            ['nombre' => 'Tirosina', 'abreviatura' => 'Tyr', 'esencial' => false, 'orden' => 10],
            ['nombre' => 'Treonina', 'abreviatura' => 'Thr', 'esencial' => true, 'orden' => 11],
            ['nombre' => 'Triptófano', 'abreviatura' => 'Trp', 'esencial' => true, 'orden' => 12],
            ['nombre' => 'Valina', 'abreviatura' => 'Val', 'esencial' => true, 'orden' => 13],
        ];
        
        foreach ($aminoacidos as $amino) {
            Aminoacido::firstOrCreate(
                ['nombre' => $amino['nombre']],
                $amino
            );
        }
    }
    
    private function seedMinerales()
    {
        $minerales = [
            ['nombre' => 'Calcio', 'simbolo' => 'Ca', 'unidad' => '%', 'esencial' => true, 'orden' => 1],
            ['nombre' => 'Fósforo', 'simbolo' => 'P', 'unidad' => '%', 'esencial' => true, 'orden' => 2],
            ['nombre' => 'Magnesio', 'simbolo' => 'Mg', 'unidad' => '%', 'esencial' => true, 'orden' => 3],
            ['nombre' => 'Potasio', 'simbolo' => 'K', 'unidad' => '%', 'esencial' => true, 'orden' => 4],
            ['nombre' => 'Sodio', 'simbolo' => 'Na', 'unidad' => '%', 'esencial' => true, 'orden' => 5],
            ['nombre' => 'Cloro', 'simbolo' => 'Cl', 'unidad' => '%', 'esencial' => true, 'orden' => 6],
            ['nombre' => 'Cobalto', 'simbolo' => 'Co', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 7],
            ['nombre' => 'Cobre', 'simbolo' => 'Cu', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 8],
            ['nombre' => 'Yodo', 'simbolo' => 'I', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 9],
            ['nombre' => 'Hierro', 'simbolo' => 'Fe', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 10],
            ['nombre' => 'Manganeso', 'simbolo' => 'Mn', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 11],
            ['nombre' => 'Selenio', 'simbolo' => 'Se', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 12],
            ['nombre' => 'Zinc', 'simbolo' => 'Zn', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 13],
        ];
        
        foreach ($minerales as $mineral) {
            Mineral::firstOrCreate(
                ['nombre' => $mineral['nombre']],
                $mineral
            );
        }
    }
    
    private function seedVitaminas()
    {
        $vitaminas = [
            ['nombre' => 'Vitamina A', 'tipo' => 'Liposoluble', 'unidad' => 'UI/kg', 'esencial' => true, 'orden' => 1],
            ['nombre' => 'Vitamina D', 'tipo' => 'Liposoluble', 'unidad' => 'UI/kg', 'esencial' => true, 'orden' => 2],
            ['nombre' => 'Vitamina E', 'tipo' => 'Liposoluble', 'unidad' => 'UI/kg', 'esencial' => true, 'orden' => 3],
            ['nombre' => 'Vitamina K', 'tipo' => 'Liposoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 4],
            ['nombre' => 'Biotina', 'tipo' => 'Hidrosoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 5],
            ['nombre' => 'Colina', 'tipo' => 'Hidrosoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 6],
            ['nombre' => 'Ácido Fólico', 'tipo' => 'Hidrosoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 7],
            ['nombre' => 'Niacina', 'tipo' => 'Hidrosoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 8],
            ['nombre' => 'Ácido Pantoténico', 'tipo' => 'Hidrosoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 9],
            ['nombre' => 'Riboflavina', 'tipo' => 'Hidrosoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 10],
            ['nombre' => 'Tiamina', 'tipo' => 'Hidrosoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 11],
            ['nombre' => 'Vitamina B6', 'tipo' => 'Hidrosoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 12],
            ['nombre' => 'Vitamina B12', 'tipo' => 'Hidrosoluble', 'unidad' => 'mg/kg', 'esencial' => true, 'orden' => 13],
        ];
        
        foreach ($vitaminas as $vitamina) {
            Vitamina::firstOrCreate(
                ['nombre' => $vitamina['nombre']],
                $vitamina
            );
        }
    }
}