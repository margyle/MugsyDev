module.exports = {
  apps: [
    {
      name: '🏰 Watchtower',
      script: './scripts/start_watchtower.sh',
      cwd: '/home/maos/Mugsy/dev/Operator',
      wait_ready: true,
      listen_timeout: 10000,
      kill_timeout: 3000
    },
    {
      name: '☎️ Operator',
      script: './scripts/start_operator.sh',
      cwd: '/home/maos/Mugsy/dev/Operator',
      wait_ready: true,
      listen_timeout: 10000,
      kill_timeout: 3000,
      depends_on: ['watchtower'],
      autorestart: true,
      watch: false,
      max_restarts: 5,
      min_uptime: "30s",
      listen_timeout: 5000,
      kill_timeout: 5000,
      exp_backoff_restart_delay: 100,
    },
    {
      name: '📡 RFID',
      script: './scripts/start_rfid.sh',
      cwd: '/home/maos/Mugsy/dev/Operator',
      wait_ready: true,
      listen_timeout: 10000,
      kill_timeout: 3000,
      log_date_format: "YYYY-MM-DD HH:mm:ss Z"
    },
    {
      name: '🫗 Mug Weight',
      script: './scripts/start_mug_weight.sh',
      cwd: '/home/maos/Mugsy/dev/Operator',
      wait_ready: true,
      listen_timeout: 10000,
      kill_timeout: 3000,
      log_date_format: "YYYY-MM-DD HH:mm:ss Z"
    },
    {
      name: '🌀 Cone Weight',
      script: './scripts/start_cone_weight.sh',
      cwd: '/home/maos/Mugsy/dev/Operator',
      wait_ready: true,
      listen_timeout: 10000,
      kill_timeout: 3000,
      log_date_format: "YYYY-MM-DD HH:mm:ss Z"
    },
    {
      name: '💧 Pump Control',
      script: './scripts/start_pump_service.sh',
      cwd: '/home/maos/Mugsy/dev/Operator',
      wait_ready: true,
      listen_timeout: 10000,
      kill_timeout: 3000,
      log_date_format: "YYYY-MM-DD HH:mm:ss Z"
    },
  ]
};