@extends('layouts.main')

@section('title', 'BOLETIM')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">BOLETIM</h2>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <th scope="row" class="text-left">ALUNO: {{ $aluno->nome }}</th>
                            <td class="text-left">TURMA: {{ $aluno->turma_nome }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center mb-3">NOTAS</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">DISCIPLINAS</th>
                            @foreach ($diarios as $diario)
                                <th scope="col"> {{ $diario->periodo_nome }}</th>                       
                            @endforeach
                            <th scope="col"> {{ 'NOTA FINAL' }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disciplinas as $disciplina)
                            <tr>
                                <th scope="row"> {{$disciplina->disciplina_nome}} </th>
                                @foreach ($avaliacoes as $avaliacao)
                                    @if ($avaliacao->disciplina_id == $disciplina->disciplina_id)
                                        @foreach ($notas_periodos as $nota_periodo)
                                            @if ($nota_periodo->avaliacao_id == $avaliacao->avaliacao_id && $nota_periodo->disciplina_id == $avaliacao->disciplina_id)
                                                <td class="@if($nota_periodo->valor_nota < 70) text-danger @else text-primary @endif"> {{ $nota_periodo->valor_nota }} </td>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                @foreach ($notas_finais as $nota_final)
                                    @if ($nota_final['disciplina_id'] == $disciplina->disciplina_id)                                       
                                        <td class="@if($nota_final['valor_nota'] < 70) text-danger @else text-primary @endif"> {{$nota_final['valor_nota']}} </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>    
                        <tr>
                            <th scope="col"></th>
                            @foreach ($diarios as $diario)
                                <th scope="col"> {{ $diario->periodo_nome }} </th>                       
                            @endforeach
                            <th scope="col"> {{ 'MAIOR NOTA FINAL' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">NOTA MAXIMA</th>
                            <?php
                            $maiores_notas_periodos = array_fill(1, 4, 0);
                            $maior_nota_final = 0;

                            foreach ($disciplinas as $disciplina) {
                                $notas_por_periodo = array_fill(1, 4, 0);

                                foreach ($avaliacoes as $avaliacao) {
                                    if ($avaliacao->disciplina_id == $disciplina->disciplina_id) {
                                        foreach ($notas_periodos as $nota_periodo) {
                                            if ($nota_periodo->avaliacao_id == $avaliacao->avaliacao_id && $nota_periodo->disciplina_id == $avaliacao->disciplina_id) {
                                                $notas_por_periodo[$nota_periodo->periodo_id] = max($notas_por_periodo[$nota_periodo->periodo_id], $nota_periodo->valor_nota);
                                            }
                                        }
                                    }
                                }

                                foreach ($notas_por_periodo as $periodo => $nota_periodo) {
                                    $maiores_notas_periodos[$periodo] = max($maiores_notas_periodos[$periodo], $nota_periodo);
                                }

                                foreach ($notas_finais as $nota_final) {
                                    if ($nota_final['disciplina_id'] == $disciplina->disciplina_id) {
                                        $maior_nota_final = max($maior_nota_final, $nota_final['valor_nota']);
                                    }
                                }
                            }

                            foreach ($maiores_notas_periodos as $periodo => $maior_nota_periodo) {
                                ?><td class="@if($maior_nota_periodo < 70) text-danger @else text-primary @endif"> {{ $maior_nota_periodo }} </td><?php
                            }

                            ?><td class="@if($maior_nota_final < 70) text-danger @else text-primary @endif"> {{ $maior_nota_final }} </td><?php
                            ?>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            @foreach ($diarios as $diario)
                                <th scope="col"> {{ $diario->periodo_nome }} </th>                       
                            @endforeach
                            <th scope="col"> {{ 'MENOR NOTA FINAL' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">NOTA MINIMA</th>
                            <?php
                            $menores_notas_periodos = array_fill(1, 4, PHP_INT_MAX);
                            $menor_nota_final = PHP_INT_MAX;

                            foreach ($disciplinas as $disciplina) {
                                $notas_por_periodo = array_fill(1, 4, PHP_INT_MAX);

                                foreach ($avaliacoes as $avaliacao) {
                                    if ($avaliacao->disciplina_id == $disciplina->disciplina_id) {
                                        foreach ($notas_periodos as $nota_periodo) {
                                            if ($nota_periodo->avaliacao_id == $avaliacao->avaliacao_id && $nota_periodo->disciplina_id == $avaliacao->disciplina_id) {
                                                $notas_por_periodo[$nota_periodo->periodo_id] = min($notas_por_periodo[$nota_periodo->periodo_id], $nota_periodo->valor_nota);
                                            }
                                        }
                                    }
                                }

                                foreach ($notas_por_periodo as $periodo => $nota_periodo) {
                                    $menores_notas_periodos[$periodo] = min($menores_notas_periodos[$periodo], $nota_periodo);
                                }

                                foreach ($notas_finais as $nota_final) {
                                    if ($nota_final['disciplina_id'] == $disciplina->disciplina_id) {
                                        $menor_nota_final = min($menor_nota_final, $nota_final['valor_nota']);
                                    }
                                }
                            }

                            foreach ($menores_notas_periodos as $periodo => $menor_nota_periodo) {
                                ?><td class="@if($menor_nota_periodo < 70) text-danger @else text-primary @endif"> {{ $menor_nota_periodo }} </td><?php
                            }

                            ?><td class="@if($menor_nota_final < 70) text-danger @else text-primary @endif"> {{ $menor_nota_final }} </td><?php
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
