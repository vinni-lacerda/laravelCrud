# API REST de Gerenciamento de Usuários

Uma API REST profissional e corporativa desenvolvida com **Laravel 13**, implementando padrões modernos de segurança, validação e tratamento de erros.

## ✨ Características Principais

- ✅ **Validação Robusta** - Form Requests com mensagens personalizadas em português
- ✅ **Exceções Customizadas** - Tratamento de erros com `ApiException` e `Handler`
- ✅ **Resources de API** - Transformação controlada de dados com `UserResource`
- ✅ **Respostas Padronizadas** - Trait `ApiResponse` para JSON consistente
- ✅ **Segurança** - Senhas hashed, ocultamento de dados sensíveis
- ✅ **Paginação** - Listagens com query strings preservadas
- ✅ **Status HTTP Corretos** - Seguindo padrões REST
- ✅ **Casting Automático** - Datas e tipos de dados manipulados automaticamente

---

## 🚀 Instalação e Configuração

### Pré-requisitos

- PHP 8.5+
- Composer
- Node.js / npm
- Banco de dados (MySQL/PostgreSQL)

### Passo a Passo

```bash
# 1. Instalar dependências PHP
composer install

# 2. Gerar chave da aplicação
php artisan key:generate

# 3. Configurar banco de dados no .env
# Edite DATABASE_URL com suas credenciais

# 4. Executar migrações
php artisan migrate

# 5. (Opcional) Popular com dados de teste
php artisan db:seed

# 6. Iniciar servidor
php artisan serve
```

A API estará disponível em: **http://localhost:8000/api**

---

## �️ Front-end (React) - Interface Gráfica

Para testar a API através de uma interface gráfica, você pode executar o front-end React incluído no projeto:

```bash
# 1. Instalar dependências do front-end
npm install

# 2. Executar o servidor de desenvolvimento
npm run dev
```

O front-end estará disponível em: **http://localhost:5173** (ou porta indicada pelo Vite)

**Nota:** Certifique-se de que a API Laravel esteja rodando em `http://localhost:8000` para que o front-end possa se conectar corretamente.

---

## �📚 Endpoints da API

### BASE URL

```
http://localhost:8000/api
```

### 1️⃣ Listar Usuários (Paginado)

```http
GET /users?per_page=10
```

**Resposta (200 OK):**

```json
{
    "message": "Usuários carregados com sucesso.",
    "data": [
        {
            "id": 1,
            "name": "João Silva",
            "email": "joao@example.com",
            "date_of_birth": "1990-05-15",
            "created_at": "2026-04-20 10:30:45",
            "updated_at": "2026-04-20 10:30:45"
        }
    ]
}
```

### 2️⃣ Obter Usuário Específico

```http
GET /users/{id}
```

**Resposta (200 OK):**

```json
{
    "message": "Usuário encontrado com sucesso.",
    "data": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@example.com",
        "date_of_birth": "1990-05-15",
        "created_at": "2026-04-20 10:30:45",
        "updated_at": "2026-04-20 10:30:45"
    }
}
```

**Erro (404 Not Found):**

```json
{
    "message": "Registro não encontrado."
}
```

### 3️⃣ Criar Novo Usuário

```http
POST /users
Content-Type: application/json
```

**Body:**

```json
{
    "name": "Maria Santos",
    "email": "maria@example.com",
    "password": "SenhaForte123!",
    "password_confirmation": "SenhaForte123!",
    "date_of_birth": "1995-08-20"
}
```

**Resposta (201 Created):**

```json
{
    "message": "Usuário criado com sucesso.",
    "data": {
        "id": 2,
        "name": "Maria Santos",
        "email": "maria@example.com",
        "date_of_birth": "1995-08-20",
        "created_at": "2026-04-20 14:22:10",
        "updated_at": "2026-04-20 14:22:10"
    }
}
```

**Erro de Validação (422 Unprocessable Entity):**

```json
{
    "message": "Falha de validação.",
    "errors": {
        "email": ["Este e-mail já está cadastrado."],
        "password": ["A senha deve ter ao menos 8 caracteres."]
    }
}
```

### 4️⃣ Atualizar Usuário

```http
PUT /users/{id}
Content-Type: application/json
```

**Body (Todos os campos opcionais):**

```json
{
    "name": "Maria Silva",
    "email": "maria.silva@example.com",
    "password": "NovaSenha123!",
    "password_confirmation": "NovaSenha123!",
    "date_of_birth": "1995-08-20"
}
```

**Resposta (200 OK):**

```json
{
    "message": "Usuário atualizado com sucesso.",
    "data": {
        "id": 2,
        "name": "Maria Silva",
        "email": "maria.silva@example.com",
        "date_of_birth": "1995-08-20",
        "created_at": "2026-04-20 14:22:10",
        "updated_at": "2026-04-20 15:45:20"
    }
}
```

**⚠️ Nota Importante:** A senha é **totalmente opcional** no update. Você pode atualizar nome/email sem enviar senha.

### 5️⃣ Deletar Usuário

```http
DELETE /users/{id}
```

**Resposta (204 No Content):**

```json
{
    "message": "Usuário removido com sucesso.",
    "data": null
}
```

---

## 🏗️ Arquitetura e Padrões

### Estrutura de Diretórios

```
app/
├── Exceptions/
│   ├── ApiException.php          # Exceção personalizada para API
│   └── Handler.php                # Renderizador de exceções para JSON
├── Http/
│   ├── Controllers/
│   │   └── UserController.php     # Controlador com lógica CRUD
│   ├── Requests/
│   │   ├── StoreUserRequest.php   # Validação para criação
│   │   └── UpdateUserRequest.php  # Validação para atualização
│   └── Resources/
│       └── UserResource.php       # Transformação de resposta JSON
├── Models/
│   └── User.php                   # Modelo Eloquent com proteções
└── Traits/
    └── ApiResponse.php            # Respostas JSON padronizadas
```

### Padrões de Desenvolvimento

#### 1. **Form Requests** (Validação Centralizada)

Validações são definidas em classes dedicadas, não no controller:

```php
// StoreUserRequest.php
public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'date_of_birth' => ['nullable', 'date', 'before:today'],
    ];
}
```

**Vantagens:**

- Separação de responsabilidades
- Reusabilidade em múltiplos contextos
- Mensagens de erro em português

#### 2. **API Resources** (Transformação de Dados)

Controlam exatamente quais dados são expostos:

```php
// UserResource.php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'date_of_birth' => optional($this->date_of_birth)->toDateString(),
    ];
}
```

**Vantagens:**

- Senhas nunca são expostas
- Datas formatadas corretamente
- Controle total sobre a resposta

#### 3. **ApiResponse Trait** (Respostas Consistentes)

Padroniza o formato de resposta JSON:

```php
use ApiResponse;

return $this->successResponse(
    UserResource::collection($users),
    'Usuários carregados com sucesso.',
    200
);
```

**Resposta:**

```json
{
  "message": "...",
  "data": [...]
}
```

#### 4. **ApiException** (Erros Personalizados)

Para lançar erros de lógica de negócio:

```php
throw new ApiException(
    'Usuário inativo',
    403,
    'user_inactive'
);
```

O `Handler` converte automaticamente para JSON com status correto.

#### 5. **Model com Segurança**

O modelo `User` implementa proteções:

```php
protected array $fillable = ['name', 'email', 'password', 'date_of_birth'];
protected array $hidden = ['password', 'remember_token'];
protected array $casts = [
    'password' => 'hashed',      // Hash automático ao salvar
    'date_of_birth' => 'date',
    'email_verified_at' => 'datetime',
];
```

---

## 🔒 Segurança

### Proteções Implementadas

| Proteção                       | Implementação                                             |
| ------------------------------ | --------------------------------------------------------- |
| **Senhas Hashed**              | Cast `hashed` no modelo - bcrypt automático               |
| **Campos Ocultos**             | `password` e `remember_token` nunca na resposta           |
| **Email Único**                | Validação única na criação, ignorando self na atualização |
| **Validação Forte**            | Mínimo 8 caracteres, confirmação de senha obrigatória     |
| **Atribuição em Massa Segura** | Apenas campos em `$fillable` podem ser atribuídos         |
| **Datas Validadas**            | Não pode ser hoje ou no futuro                            |

---

## 📋 Regras de Validação

### Criação de Usuário (`StoreUserRequest`)

| Campo           | Regra                                     | Mensagem de Erro                             |
| --------------- | ----------------------------------------- | -------------------------------------------- |
| `name`          | Obrigatório, string, máx 255              | "O nome é obrigatório."                      |
| `email`         | Obrigatório, email válido, único          | "O e-mail é obrigatório." / "Já cadastrado." |
| `password`      | Obrigatório, mín 8 caracteres, confirmado | "Obrigatória." / "Mínimo 8 caracteres."      |
| `date_of_birth` | Opcional, data válida, antes de hoje      | "Data inválida." / "Não pode ser futuro."    |

### Atualização de Usuário (`UpdateUserRequest`)

| Campo           | Regra                                   | Nota Especial                |
| --------------- | --------------------------------------- | ---------------------------- |
| `name`          | Obrigatório, string, máx 255            | Sempre obrigatório           |
| `email`         | Obrigatório, email, único (ignore self) | **E-mail atual é permitido** |
| `password`      | Opcional, mín 8 caracteres, confirmado  | **Totalmente opcional**      |
| `date_of_birth` | Opcional, data válida, antes de hoje    | Pode permanecer vazio        |

---

## 🧪 Exemplos de Testes com cURL

```bash
# Criar usuário
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@example.com",
    "password": "Senha123456",
    "password_confirmation": "Senha123456",
    "date_of_birth": "1990-05-15"
  }'

# Listar usuários
curl http://localhost:8000/api/users?per_page=10

# Obter usuário
curl http://localhost:8000/api/users/1

# Atualizar (apenas nome e email, sem alterar senha)
curl -X PUT http://localhost:8000/api/users/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva Atualizado",
    "email": "joao.novo@example.com"
  }'

# Deletar
curl -X DELETE http://localhost:8000/api/users/1
```

---

## 🛠️ Desenvolvimento

### Formatar Código com Pint

```bash
# Verificar estilo
vendor/bin/pint --test --dirty --format agent

# Corrigir automaticamente
vendor/bin/pint --dirty --format agent
```

### Executar Testes

```bash
# Todos os testes
php artisan test

# Teste específico
php artisan test --filter=UserControllerTest
```

### Artisan Úteis

```bash
# Listar todas as rotas
php artisan route:list

# Filtrar rotas por path
php artisan route:list --path=api

# Verificar configurações
php artisan config:show app.name
```

---

## 📊 Fluxo de Requisição

```
┌─ HTTP Request ─────────────────────┐
│ POST /api/users                     │
│ { name, email, password, ... }      │
└─────────────────────────────────────┘
              ↓
┌─ Route & Controller ────────────────┐
│ → UserController@store              │
└─────────────────────────────────────┘
              ↓
┌─ Form Request Validation ───────────┐
│ StoreUserRequest::rules()           │
│ Valida email único, senha forte...  │
└─────────────────────────────────────┘
              ↓
┌─ Model Creation ────────────────────┐
│ User::create($validated)            │
│ - password auto-hashed              │
│ - date_of_birth auto-cast           │
└─────────────────────────────────────┘
              ↓
┌─ Resource Transformation ───────────┐
│ UserResource::make($user)           │
│ - password oculto                   │
│ - datas formatadas                  │
└─────────────────────────────────────┘
              ↓
┌─ ApiResponse Trait ─────────────────┐
│ successResponse($data, $msg, 201)   │
│ { message, data }                   │
└─────────────────────────────────────┘
              ↓
┌─ JSON Response ─────────────────────┐
│ Status: 201 Created                 │
│ { message, data }                   │
└─────────────────────────────────────┘
```

**Se houver erro em qualquer etapa:**

```
Exception/ValidationError
        ↓
Handler::renderable()
        ↓
Formata como JSON com status HTTP correto
        ↓
HTTP Response com erro estruturado
```

---

## 💡 Exemplo Completo: Criar Usuário

### 1. Requisição

```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Ana Costa",
    "email": "ana@example.com",
    "password": "SenhaForte123!",
    "password_confirmation": "SenhaForte123!",
    "date_of_birth": "1992-03-10"
  }'
```

### 2. Processamento

1. Route → `UserController@store`
2. `StoreUserRequest` valida os dados
3. Se válido → `User::create($request->validated())`
    - Senha é automaticamente hashed
    - Data de nascimento é convertida para tipo `date`
4. `UserResource` transforma o modelo
    - Senha é removida (campo `hidden`)
    - Datas são formatadas
5. `ApiResponse::successResponse()` estrutura a resposta

### 3. Resposta

```json
{
    "message": "Usuário criado com sucesso.",
    "data": {
        "id": 5,
        "name": "Ana Costa",
        "email": "ana@example.com",
        "date_of_birth": "1992-03-10",
        "created_at": "2026-04-20 16:30:45",
        "updated_at": "2026-04-20 16:30:45"
    }
}
```

---

## 📖 Documentação Oficial

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel API Documentation](https://laravel.com/api)
- [PHP Documentation](https://www.php.net/docs.php)

---

## 📝 Licença

Este projeto está licenciado sob a [MIT License](https://opensource.org/licenses/MIT).

---

**Desenvolvido com ❤️ usando Laravel 13 e PHP 8.5**
