// Đường dẫn: mobile_flutter/lib/localization/app_localizations.dart

import 'package:flutter/material.dart';

class AppLocalizations {
  final Locale locale;
  static AppLocalizations? of(BuildContext context) {
    return Localizations.of<AppLocalizations>(context, AppLocalizations);
  }

  AppLocalizations(this.locale);

  static const LocalizationsDelegate<AppLocalizations> delegate = _AppLocalizationsDelegate();

  static const List<Locale> supportedLocales = [
    Locale('en', ''),
    Locale('vi', ''),
    Locale('ja', ''),
    Locale('ko', ''),
    Locale('zh', ''),
  ];

  Map<String, String> _strings = {};

  Future<void> load() async {
    _strings = await _loadLocalizations();
  }

  String translate(String key) {
    return _strings[key] ?? key;
  }
}

class _AppLocalizationsDelegate extends LocalizationsDelegate<AppLocalizations> {
  const _AppLocalizationsDelegate();

  @override
  bool isSupported(Locale locale) => AppLocalizations.supportedLocales.contains(locale);

  @override
  Future<AppLocalizations> load(Locale locale) async {
    final localizations = AppLocalizations(locale);
    await localizations.load();
    return localizations;
  }

  @override
  bool shouldReload(covariant LocalizationsDelegate<AppLocalizations> old) => false;
}

Future<Map<String, String>> _loadLocalizations() async {
  return {};
}