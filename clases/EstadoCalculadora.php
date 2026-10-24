<?php
declare(strict_types=1);


class EstadoCalculadora
{
    private const CLAVE = 'calc_estado';
    private const MAX_DIGITOS = 15;

    private string $a = '';
    private string $operador = '';
    private string $b = '';
    private string $expresion = '';
    private string $error = '';
    private bool $mostrandoResultado = false;

    public function __construct()
    {
        foreach ($_SESSION[self::CLAVE] ?? [] as $campo => $valor) {
            if (property_exists($this, $campo)) {
                $this->$campo = $valor;
            }
        }
    }

    public function pulsar(string $tecla): void
    {
        if (preg_match('/^[0-9]$/', $tecla) || $tecla === '.') {
            $this->escribir($tecla);
        } elseif (isset(Calculadora::SIMBOLOS[$tecla])) {
            $this->elegirOperador($tecla);
        } elseif ($tecla === 'signo') {
            $this->cambiarSigno();
        } elseif ($tecla === 'retroceso') {
            $this->retroceso();
        } elseif ($tecla === 'limpiar') {
            $this->reiniciar();
        } elseif ($tecla === 'igual') {
            $this->igual();
        }

        $this->guardar();
    }

    public function reiniciar(): void
    {
        $this->a = $this->operador = $this->b = $this->expresion = $this->error = '';
        $this->mostrandoResultado = false;
        $this->guardar();
    }

    public function pantallaOperacion(): string
    {
        if ($this->operador !== '') {
            return $this->a . ' ' . Calculadora::SIMBOLOS[$this->operador];
        }
        return $this->expresion;
    }

    public function pantallaResultado(): string
    {
        if ($this->error !== '') {
            return $this->error;
        }
        $valor = $this->operador === '' ? $this->a : $this->b;
        return $valor === '' ? '0' : $valor;
    }

    public function hayError(): bool
    {
        return $this->error !== '';
    }

    private function escribir(string $caracter): void
    {
        if ($this->error !== '') {
            $this->error = '';
            $this->expresion = '';
        }
        $lado = $this->operador === '' ? 'a' : 'b';

        if ($lado === 'a' && $this->mostrandoResultado) {
            $this->a = '';
            $this->expresion = '';
            $this->mostrandoResultado = false;
        }

        $valor = $this->$lado;
        if ($caracter === '.' && str_contains($valor, '.')) {
            return;
        }
        if ($caracter === '.' && ($valor === '' || $valor === '-')) {
            $valor .= '0';
        }
        if (($valor === '0' || $valor === '-0') && $caracter !== '.') {
            $valor = rtrim($valor, '0');
        }
        if (strlen(str_replace(['-', '.'], '', $valor)) >= self::MAX_DIGITOS) {
            return;
        }

        $this->$lado = $valor . $caracter;
    }

    private function elegirOperador(string $operador): void
    {
        $this->error = '';
        if ($this->a === '' || $this->a === '-') {
            $this->a = '0';
        }
        $this->mostrandoResultado = false;

        if ($this->b !== '' && $this->b !== '-') {
            $this->calcular();
            if ($this->error !== '') {
                return;
            }
        }

        $this->operador = $operador;
        $this->expresion = '';
    }

    private function cambiarSigno(): void
    {
        $lado = $this->operador === '' ? 'a' : 'b';
        $this->mostrandoResultado = false;
        $valor = $this->$lado;
        $this->$lado = str_starts_with($valor, '-') ? substr($valor, 1) : '-' . $valor;
    }

    private function retroceso(): void
    {
        $this->error = '';
        if ($this->b !== '') {
            $this->b = substr($this->b, 0, -1);
        } elseif ($this->operador !== '') {
            $this->operador = '';
        } else {
            $this->a = substr($this->a, 0, -1);
            $this->mostrandoResultado = false;
        }
    }

    private function igual(): void
    {
        if ($this->operador === '' || $this->b === '' || $this->b === '-') {
            $this->expresion = 'FALTA UN NÚMERO';
            return;
        }
        $this->calcular();
    }

    private function calcular(): void
    {
        $a = (float) $this->a;
        $b = (float) $this->b;
        $operacion = Calculadora::formatear($a) . ' ' . Calculadora::SIMBOLOS[$this->operador] . ' ' . Calculadora::formatear($b);

        try {
            $resultado = Calculadora::operar($a, $this->operador, $b);
            Calculadora::agregarHistorial($operacion, $resultado);
            $this->a = Calculadora::formatear($resultado);
            $this->mostrandoResultado = true;
        } catch (Exception $ex) {
            $this->error = $ex->getMessage();
            $this->a = '';
            $this->mostrandoResultado = false;
        }

        $this->expresion = $operacion . ' =';
        $this->operador = '';
        $this->b = '';
    }

    private function guardar(): void
    {
        $_SESSION[self::CLAVE] = get_object_vars($this);
    }
}
