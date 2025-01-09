## Sistema de Reservas

Estr é um projeto prático de reserva de propriedades similar a um Airbnb.

### Funcionalidades

1. Reservar propriedades para períodos específicos
  a. A propriedade precisa estar disponível no período solicitado.
  b. O número de hóspedes não pode exceder a capacidade máxima da proriedade.
O preço total da reserva é calculado com base no preço por noite da propriedade e no número de hóspedes.
**Desconto automático:** Por uma reserva de 7 ou mais noites, aplica-se um desconto automático de 10%.

2. Cancelar reservas com políticas de reembolso
**Políticas:**
 a. 7 dias ou mais antes do check-in: Reembolso total do valor pago.
 b. 1 a 6 dias antes do check-in: Reembolso de 50% do valor pago.
 c. Menos de 1 dia: Reembolso não permitido.
Cancelamentos atualizam o status da reserva para **CANCELLED** e liberam a propriedade para novas reservas.
O sistema impede o cancelamento de reservas que já foram canceladas.


## Para executar o projeto.

Após clonar o projeto, crie uma cópia do .env.example para .env e em seguida execute: `docker compose up -d`

Para realizar os testes execute `php artisan test`
