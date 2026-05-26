class Validators {
  static String? validateEmail(String? email) {
    if (email == null || email.isEmpty) return 'Email is required';
    final regex = RegExp(r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$');
    if (!regex.hasMatch(email)) return 'Enter a valid email';
    return null;
  }
  
  static String? validatePassword(String? password) {
    if (password == null || password.isEmpty) return 'Password is required';
    if (password.length < 8) return 'Password must be at least 8 characters';
    if (!password.contains(RegExp(r'[A-Z]'))) return 'Password must contain an uppercase letter';
    if (!password.contains(RegExp(r'[a-z]'))) return 'Password must contain a lowercase letter';
    if (!password.contains(RegExp(r'[0-9]'))) return 'Password must contain a number';
    if (!password.contains(RegExp(r'[!@#$%^&*(),.?":{}|<>]'))) return 'Password must contain a special character';
    return null;
  }
  
  static String? validateConfirmPassword(String? password, String? confirmPassword) {
    if (confirmPassword == null || confirmPassword.isEmpty) return 'Please confirm your password';
    if (password != confirmPassword) return 'Passwords do not match';
    return null;
  }
  
  static String? validateRequired(String? value, String fieldName) {
    if (value == null || value.trim().isEmpty) return '$fieldName is required';
    return null;
  }
  
  static String? validateMinLength(String? value, int minLength, String fieldName) {
    if (value != null && value.length < minLength) return '$fieldName must be at least $minLength characters';
    return null;
  }
  
  static bool isStrongPassword(String password) {
    return password.length >= 12 &&
        password.contains(RegExp(r'[A-Z]')) &&
        password.contains(RegExp(r'[a-z]')) &&
        password.contains(RegExp(r'[0-9]')) &&
        password.contains(RegExp(r'[!@#$%^&*(),.?":{}|<>]'));
  }
}