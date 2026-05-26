// Đường dẫn: mobile_flutter/lib/utils/url_launcher_helper.dart

import 'package:url_launcher/url_launcher.dart';

class UrlLauncherHelper {
  static Future<bool> launchUrl(String url) async {
    final uri = Uri.parse(url);
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
      return true;
    }
    return false;
  }

  static Future<bool> launchEmail(String email, {String? subject}) async {
    final uri = Uri.parse('mailto:$email${subject != null ? '?subject=$subject' : ''}');
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
      return true;
    }
    return false;
  }

  static Future<bool> launchPhone(String phone) async {
    final uri = Uri.parse('tel:$phone');
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
      return true;
    }
    return false;
  }

  static Future<bool> launchSms(String phone, {String? body}) async {
    final uri = Uri.parse('sms:$phone${body != null ? '?body=$body' : ''}');
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
      return true;
    }
    return false;
  }
}