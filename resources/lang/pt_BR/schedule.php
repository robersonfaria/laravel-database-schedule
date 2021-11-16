<?php
return [
    'titles' => [
        'list' => 'Lista de Tarefas Agendadas',
        'create' => 'Criar nova tarefa agendada',
        'edit' => 'Editar tarefa agendada',
        'show' => 'Histórico de execução'
    ],
    'fields' => [
        'command' => 'Comando',
        'arguments' => 'Parâmetros',
        'options' => 'Opções',
        'options_with_value' => 'opções com valor',
        'expression' => 'Experessão Cron',
        'output' => 'Saída',
        'even_in_maintenance_mode' => 'Também em modo de manutenção',
        'without_overlapping' => 'Sem sobreposição',
        'on_one_server' => 'Executar tarefa agendada apenas em um servidor',
        'webhook_before' => 'URL Antes',
        'webhook_after' => 'URL Após',
        'email_output' => 'Email para enviar a saída do comando',
        'sendmail_success' => 'Enviar email em caso de sucesso na execução do comando',
        'sendmail_error' => 'Enviar email em caso de falha na execução do comando',
        'status' => 'Status',
        'actions' => 'Ações',
        'data-type' => 'Tipo de dado',
        'run_in_background' => 'Executar em segundo plano'
    ],
    'messages' => [
        'no-records-found' => 'Não foram encontrado registros.',
        'save-success' => 'Sucesso ao salvar os dados.',
        'save-error' => 'Erro ao salvar os dados.',
        'timezone' => 'Todas as tarefas agendadas serão executadas no fuso horário: ',
        'select' => 'Selecione um comando',
        'custom' => 'Comando customizado',
        'custom-command-here' => 'Digite o comando customizado (e.g. `cat /proc/cpuinfo` or `artisan db:migrate`)',
        'help-cron-expression' => 'Se necessário clique aqui e use uma ferramenta para facilitar a criação de uma expressão cron',
        'attention-type-function' => "ATENÇÃO: parâmetros do tipo 'function' são executados antes da execução da tarefa agendada e seu retorno é usado como parâmetro. Use com cuidado, isso pode gerar erros"
    ],
    'status' => [
        'active' => 'Ativa',
        'inactive' => 'Inativa'
    ],
    'buttons' => [
        'create' => 'Criar',
        'edit' => 'Editar',
        'back' => 'Voltar',
        'save' => 'Salvar',
        'inactivate' => 'Desativar',
        'activate' => 'Ativar',
        'delete' => 'Deletar',
        'history' => 'Histórico',
    ]
];
