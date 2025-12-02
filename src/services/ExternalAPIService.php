<?php
/**
 * Servicio de Web Services de Terceros
 * Integración con OpenWeather y Nominatim
 */

namespace App\Services;

require_once __DIR__ . '/../../config/config.php';

class ExternalAPIService {
    private $timeout = 5;
    private $cache = [];
    private $cache_duration = 3600; // 1 hora

    /**
     * Obtener clima actual
     */
    public function obtenerClima($ciudad, $pais = 'CO') {
        try {
            $cache_key = "weather_" . md5($ciudad);
            
            // Verificar caché
            if (isset($this->cache[$cache_key]) && (time() - $this->cache[$cache_key]['time']) < $this->cache_duration) {
                return $this->cache[$cache_key]['data'];
            }

            $url = WEATHER_API_URL . "?q=" . urlencode($ciudad) . "," . urlencode($pais) . "&appid=" . WEATHER_API_KEY . "&units=metric&lang=es";

            $response = $this->hacerSolicitud($url);

            if (!$response['success']) {
                throw new \Exception('Error al obtener el clima');
            }

            $data = json_decode($response['data'], true);

            $clima = [
                'ciudad' => $data['name'] ?? 'Desconocida',
                'pais' => $data['sys']['country'] ?? '',
                'temperatura' => $data['main']['temp'] ?? null,
                'descripcion' => $data['weather'][0]['description'] ?? '',
                'humedad' => $data['main']['humidity'] ?? null,
                'velocidad_viento' => $data['wind']['speed'] ?? null,
                'icono' => $data['weather'][0]['icon'] ?? ''
            ];

            // Guardar en caché
            $this->cache[$cache_key] = [
                'data' => $clima,
                'time' => time()
            ];

            return $clima;

        } catch (\Exception $e) {
            log_activity('API_ERROR', 'Climate API: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Geocodificar dirección (obtener coordenadas)
     */
    public function geocodificar($direccion) {
        try {
            $url = NOMINATIM_API_URL . "?q=" . urlencode($direccion) . "&format=json&limit=1";

            $response = $this->hacerSolicitud($url, ['User-Agent' => 'AppTareas/1.0']);

            if (!$response['success']) {
                throw new \Exception('Error en geocodificación');
            }

            $data = json_decode($response['data'], true);

            if (empty($data)) {
                return ['error' => 'Dirección no encontrada'];
            }

            return [
                'latitud' => $data[0]['lat'] ?? null,
                'longitud' => $data[0]['lon'] ?? null,
                'direccion' => $data[0]['display_name'] ?? '',
                'clase' => $data[0]['class'] ?? ''
            ];

        } catch (\Exception $e) {
            log_activity('API_ERROR', 'Geocoding API: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Obtener clima por coordenadas
     */
    public function obtenerClimaGeneral() {
        try {
            // API gratuita que no requiere key
            $url = "https://api.open-meteo.com/v1/forecast?latitude=4.5981&longitude=-74.0758&current=temperature_2m,weather_code,wind_speed_10m&timezone=auto";

            $response = $this->hacerSolicitud($url);

            if (!$response['success']) {
                throw new \Exception('Error al obtener clima general');
            }

            $data = json_decode($response['data'], true);

            return [
                'temperatura' => $data['current']['temperature_2m'] ?? null,
                'velocidad_viento' => $data['current']['wind_speed_10m'] ?? null,
                'codigo_clima' => $data['current']['weather_code'] ?? null
            ];

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Realizar solicitud HTTP
     */
    private function hacerSolicitud($url, $headers = []) {
        try {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $default_headers = ['Content-Type: application/json'];
            $all_headers = array_merge($default_headers, $headers);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $all_headers);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);

            curl_close($ch);

            if ($error) {
                throw new \Exception("Error cURL: $error");
            }

            if ($http_code !== 200) {
                throw new \Exception("HTTP Error: $http_code");
            }

            return [
                'success' => true,
                'data' => $response
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validar URL
     */
    public function validarURL($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
?>
