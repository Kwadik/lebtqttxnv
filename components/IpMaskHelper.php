<?php
namespace app\components;

/**
 * Хелпер для маскировки адресов IP
 */
class IpMaskHelper {

	/**
	 * Маскирует IPv4 или IPv6.
	 */
	public static function mask(string $ip): string
	{
		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			return self::maskIpv4($ip);
		}

		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
			return self::maskIpv6($ip);
		}

		// если вдруг не IP — вернуть как есть
		return $ip;
	}

	/**
	 * IPv4: скрываем 2 последних октета.
	 * 46.211.123.55 → 46.211.*.*
	 */
	private static function maskIpv4(string $ip): string
	{
		$parts = explode('.', $ip);
		$parts[2] = '**';
		$parts[3] = '**';
		return implode('.', $parts);
	}

	/**
	 * IPv6: скрываем 4 последних секции.
	 * 2001:db8:11a3:09d7:1f34:8a2e:07a0:765d → 2001:db8:11a3:09d7:****:****:****:****
	 */
	private static function maskIpv6(string $ip): string
	{
		$parts = explode(':', $ip);
		$count = count($parts);

		for ($i = max(0, $count - 4); $i < $count; $i++) {
			$parts[$i] = '****';
		}

		return implode(':', $parts);
	}
}
