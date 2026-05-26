// Đường dẫn: mobile_flutter/lib/presentation/screens/profile/language_settings_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/language_provider.dart';

class LanguageSettingsScreen extends ConsumerWidget {
  const LanguageSettingsScreen({super.key});

  final List<Map<String, dynamic>> _languages = const [
    {'code': 'en', 'name': 'English', 'native': 'English'},
    {'code': 'vi', 'name': 'Vietnamese', 'native': 'Tiếng Việt'},
    {'code': 'ja', 'name': 'Japanese', 'native': '日本語'},
    {'code': 'ko', 'name': 'Korean', 'native': '한국어'},
    {'code': 'zh', 'name': 'Chinese', 'native': '中文'},
  ];

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final currentLocale = ref.watch(languageProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Language Settings'),
      ),
      body: ListView.builder(
        itemCount: _languages.length,
        itemBuilder: (context, index) {
          final lang = _languages[index];
          final isSelected = currentLocale.languageCode == lang['code'];
          return RadioListTile(
            title: Text(lang['name']),
            subtitle: Text(lang['native']),
            value: lang['code'],
            groupValue: currentLocale.languageCode,
            onChanged: (value) {
              ref.read(languageProvider.notifier).setLanguage(Locale(value as String));
              Navigator.pop(context);
            },
            selected: isSelected,
          );
        },
      ),
    );
  }
}