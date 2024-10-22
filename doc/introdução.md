Abaixo está um exemplo de texto introdutório e explicações que você pode utilizar em conjunto com o diagrama que pretende incluir. Para isso, basta ajustar o local da imagem conforme solicitado.

---

## Introdução

O sistema de gestão de uma indústria pode envolver diversos componentes, desde o controle de peças e fornecedores até a administração de projetos e funcionários. Para ilustrar a estrutura de um banco de dados que suporta essas funcionalidades, foi desenvolvido um modelo relacional que engloba várias tabelas interconectadas. Este modelo permite o gerenciamento eficiente de peças, fornecedores, projetos, depósitos, departamentos e funcionários, garantindo que as informações fluam de forma organizada e acessível.

### Diagrama do banco de dados
![Diagrama do banco de dados](/public/preparação_saep.png)

### Explicação do Modelo Relacional

1. **Tabela `pecas`**  
   Esta tabela armazena informações sobre as peças, incluindo o ID da peça (`pec_id`), o número da peça, sua cor, valor e peso. Esses dados são essenciais para o controle de estoque e para a integração com projetos e fornecedores.

2. **Tabela `deposito`**  
   A tabela `deposito` gerencia os dados relacionados aos depósitos da indústria, incluindo um identificador único (`dep_id`), número do depósito e seu endereço. Esta tabela está relacionada diretamente à tabela `deposito_peca`, que armazena quais peças estão em quais depósitos.

3. **Tabela `fornecedor`**  
   Fornecedores são cadastrados na tabela `fornecedor`, que contém dados como o número do fornecedor, endereço e nome. Ela está ligada aos projetos e às peças fornecidas para a indústria, facilitando a rastreabilidade dos fornecedores de cada peça em um projeto específico.

4. **Tabela `projeto`**  
   A tabela `projeto` é responsável pelo armazenamento de dados referentes aos projetos, como o número do projeto, orçamento e descrição. Os projetos são peças centrais no sistema, já que agregam tanto funcionários quanto peças e fornecedores.

5. **Tabela `departamento`**  
   Os departamentos dentro da indústria estão mapeados na tabela `departamento`. Cada departamento tem um número associado e um setor específico, o que auxilia na organização interna dos funcionários.

6. **Tabela `funcionario`**  
   A tabela `funcionario` armazena dados sobre os funcionários, como número, salário, telefone, nome, além do departamento a que pertencem (`dep_id`). Essa tabela está ligada a projetos na tabela `funcionario_projeto`, onde é possível saber quais funcionários trabalham em quais projetos e por quanto tempo.

7. **Tabelas Associativas**

   - **Tabela `funcionario_projeto`**  
     Esta tabela conecta funcionários a projetos, indicando a data de início e o número de horas trabalhadas por funcionário em cada projeto.
   
   - **Tabela `projeto_fornecedor`**  
     Associa os fornecedores aos projetos nos quais participam, garantindo a integração entre as tabelas `projeto` e `fornecedor`.
   
   - **Tabela `projeto_peca`**  
     Registra as peças utilizadas em cada projeto, indicando também o fornecedor da peça, conectando assim as tabelas `pecas`, `fornecedor` e `projeto`.

   - **Tabela `deposito_peca`**  
     Relaciona quais peças estão armazenadas em quais depósitos, juntamente com a quantidade disponível de cada peça, facilitando o controle de estoque distribuído.
