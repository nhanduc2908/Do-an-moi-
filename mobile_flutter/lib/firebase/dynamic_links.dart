// Đường dẫn: mobile_flutter/lib/firebase/dynamic_links.dart

import 'package:firebase_dynamic_links/firebase_dynamic_links.dart';

class DynamicLinkService {
  final FirebaseDynamicLinks _dynamicLinks = FirebaseDynamicLinks.instance;

  Future<void> initDynamicLinks() async {
    _dynamicLinks.onLink.listen((PendingDynamicLinkData? linkData) {
      final Uri? deepLink = linkData?.link;
      if (deepLink != null) {
        // Handle deep link
      }
    });
  }

  Future<String> createDynamicLink(String screen, Map<String, String> params) async {
    final DynamicLinkParameters parameters = DynamicLinkParameters(
      uriPrefix: 'https://security-platform.page.link',
      link: Uri.parse('https://security-platform.com/$screen?${_buildParams(params)}'),
      androidParameters: const AndroidParameters(
        packageName: 'com.security.evaluation.app',
        minimumVersion: 1,
      ),
      iosParameters: const IOSParameters(
        bundleId: 'com.security.evaluation.app',
        minimumVersion: '1.0.0',
      ),
    );
    final ShortDynamicLink shortLink = await parameters.buildShortLink();
    return shortLink.shortUrl.toString();
  }

  String _buildParams(Map<String, String> params) {
    return params.entries.map((e) => '${e.key}=${e.value}').join('&');
  }
}