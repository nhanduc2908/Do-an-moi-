extension StringExtension on String {
  bool get isValidEmail {
    final emailRegex = RegExp(r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$');
    return emailRegex.hasMatch(this);
  }
  
  bool get isNotEmptyOrNull => this != null && isNotEmpty;
  
  bool get isNullOrEmpty => this == null || isEmpty;
  
  String capitalize() {
    if (isEmpty) return this;
    return this[0].toUpperCase() + substring(1);
  }
  
  String capitalizeWords() {
    if (isEmpty) return this;
    return split(' ').map((word) => word.capitalize()).join(' ');
  }
  
  String truncate(int maxLength, {String suffix = '...'}) {
    if (length <= maxLength) return this;
    return substring(0, maxLength) + suffix;
  }
  
  String toSlug() {
    return toLowerCase()
        .replaceAll(RegExp(r'[^a-z0-9\s-]'), '')
        .replaceAll(RegExp(r'\s+'), '-')
        .replaceAll(RegExp(r'-+'), '-');
  }
  
  bool isNumeric() {
    return double.tryParse(this) != null;
  }
  
  String maskEmail() {
    final parts = split('@');
    if (parts.length != 2) return this;
    final name = parts[0];
    final domain = parts[1];
    if (name.length <= 2) return this;
    final masked = name[0] + '*' * (name.length - 2) + name[name.length - 1];
    return '$masked@$domain';
  }
  
  String maskPhone() {
    if (length < 4) return this;
    final prefix = substring(0, 3);
    final suffix = substring(length - 3);
    return '$prefix****$suffix';
  }
}