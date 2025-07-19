% --- Declaraciones dinámicas ---
:- dynamic tiene/1.

% --- Base de síntomas como comentarios para referencia ---
% s1: Disnea progresiva
% s2: Tos
% s3: Infecciones frecuentes
% s4: Fiebre
% s5: Baja de peso
% s6: Extensas opacidades bilaterales
% s7: Síntomas empeoran en el trabajo
% s8: Síntomas mejoran los días libres
% s9: Síntomas mejoran en vacaciones
% s10: Reacción pleural inflamatoria
% s11: Derrame pleural
% s12: Tumor de la pleura
% s13: Cefalea
% s14: Náuseas
% s15: Vómitos
% s16: Fatiga
% s17: Cansancio
% s18: Dificultad para dormir
% s19: Poliglobulia
% s20: Manifestaciones neuropsiquiátricas
% s21: Hiperviscosidad
% s22: Hipertensión pulmonar

% --- Base de enfermedades con sus listas de síntomas ---
base_enfermedad('Silicosis', [s1, s2, s3]).
base_enfermedad('Silicosis Aguda', [s1, s4, s5, s6]).
base_enfermedad('Asma Ocupacional', [s7, s8, s9]).
base_enfermedad('Derrame Pleural Benigno', [s10, s11]).
base_enfermedad('Mesotelioma', [s11, s12, s1, s2, s5]).
base_enfermedad('Mal Agudo de Montaña', [s13, s14, s15, s16, s17, s18]).
base_enfermedad('Enfermedad Cronica de Montana', [s19, s20, s21, s22]).

% --- Diagnóstico principal ---
test(Sintomas) :-
    limpiar,
    lista(Sintomas).

% --- Insertar síntomas ---
lista([]) :-
    findall((Nombre, P), coincidencia_enfermedad(Nombre, P), Coincidencias),
    incluir_diagnostico(Coincidencias).

lista([H|T]) :-
    assert(tiene(H)),
    lista(T).

% --- Diagnóstico por coincidencia ---
coincidencia_enfermedad(Nombre, Porcentaje) :-
    base_enfermedad(Nombre, Sintomas),
    incluir_cuantos(Sintomas, Coinciden, Total),
    Total > 0,
    Porcentaje is (Coinciden / Total) * 100.

incluir_cuantos([], 0, 0).
incluir_cuantos([S|Resto], Coinciden, Total) :-
    incluir_cuantos(Resto, CoincidenResto, TotalResto),
    ( tiene(S) -> Coinciden is CoincidenResto + 1 ; Coinciden = CoincidenResto ),
    Total is TotalResto + 1.

% --- Clasificación de resultados ---
incluir_diagnostico([]) :-
    write('No se encontró ninguna enfermedad relacionada con los síntomas proporcionados.'), nl.

incluir_diagnostico(Coincidencias) :-
    include(es_diagnostico_completo, Coincidencias, Completas),
    include(es_diagnostico_parcial, Coincidencias, Parciales),
    mostrar_diagnostico(Completas, Parciales).

es_diagnostico_completo((_, P)) :- P =:= 100.
es_diagnostico_parcial((_, P)) :- P >= 60, P < 100.

mostrar_diagnostico([], []) :-
    write('No se encontró ninguna enfermedad relacionada con los síntomas proporcionados.'), nl.

mostrar_diagnostico(Completas, []) :-
    write('Diagnóstico confirmado. Usted presenta: '), nl,
    imprimir_enfermedades(Completas).

mostrar_diagnostico([], Parciales) :-
    write('Los síntomas que presentas están relacionados con: '), nl,
    imprimir_enfermedades(Parciales).

mostrar_diagnostico(Completas, Parciales) :-
    write('Diagnóstico confirmado: '), nl,
    imprimir_enfermedades(Completas),
    nl,
    write('Otras posibles enfermedades relacionadas: '), nl,
    imprimir_enfermedades(Parciales).

% --- Mostrar resultados ---
imprimir_enfermedades([]).
imprimir_enfermedades([(Nombre, Porc)|T]) :-
    format('- ~w (~0f%)~n', [Nombre, Porc]),
    imprimir_enfermedades(T).

% --- Limpieza de hechos previos ---
limpiar :-
    retract(tiene(_)),
    fail.
limpiar.