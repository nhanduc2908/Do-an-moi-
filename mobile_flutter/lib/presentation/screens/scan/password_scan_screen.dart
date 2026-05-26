// Đường dẫn: mobile_flutter/lib/presentation/screens/scan/password_scan_screen.dart

import 'package:flutter/material.dart';

class PasswordScanScreen extends StatefulWidget {
  const PasswordScanScreen({super.key});

  @override
  State<PasswordScanScreen> createState() => _PasswordScanScreenState();
}

class _PasswordScanScreenState extends State<PasswordScanScreen> {
  final TextEditingController _passwordController = TextEditingController();
  bool _isScanning = false;
  Map<String, dynamic>? _result;
  bool _isPasswordVisible = false;

  Future<void> _scanPassword() async {
    if (_passwordController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter a password to scan')),
      );
      return;
    }

    setState(() {
      _isScanning = true;
      _result = null;
    });

    await Future.delayed(const Duration(seconds: 2));

    final password = _passwordController.text;
    final isStrong = _checkPasswordStrength(password);
    final leaked = await _checkIfLeaked(password);

    setState(() {
      _isScanning = false;
      _result = {
        'password': password,
        'strength': _getStrengthLevel(password),
        'strengthScore': _calculateStrengthScore(password),
        'length': password.length,
        'hasUppercase': password.contains(RegExp(r'[A-Z]')),
        'hasLowercase': password.contains(RegExp(r'[a-z]')),
        'hasNumbers': password.contains(RegExp(r'[0-9]')),
        'hasSpecial': password.contains(RegExp(r'[!@#$%^&*(),.?":{}|<>]')),
        'isLeaked': leaked,
        'isStrong': isStrong,
      };
    });
  }

  bool _checkPasswordStrength(String password) {
    return password.length >= 12 &&
        password.contains(RegExp(r'[A-Z]')) &&
        password.contains(RegExp(r'[a-z]')) &&
        password.contains(RegExp(r'[0-9]')) &&
        password.contains(RegExp(r'[!@#$%^&*(),.?":{}|<>]'));
  }

  Future<bool> _checkIfLeaked(String password) async {
    // Simulate API call to check if password has been leaked
    await Future.delayed(const Duration(milliseconds: 500));
    return false;
  }

  String _getStrengthLevel(String password) {
    final score = _calculateStrengthScore(password);
    if (score >= 80) return 'Very Strong';
    if (score >= 60) return 'Strong';
    if (score >= 40) return 'Fair';
    if (score >= 20) return 'Weak';
    return 'Very Weak';
  }

  int _calculateStrengthScore(String password) {
    int score = 0;
    if (password.length >= 8) score += 10;
    if (password.length >= 12) score += 10;
    if (password.contains(RegExp(r'[A-Z]'))) score += 15;
    if (password.contains(RegExp(r'[a-z]'))) score += 10;
    if (password.contains(RegExp(r'[0-9]'))) score += 15;
    if (password.contains(RegExp(r'[!@#$%^&*(),.?":{}|<>]'))) score += 20;
    return score;
  }

  Color _getStrengthColor(String strength) {
    switch (strength) {
      case 'Very Strong': return Colors.green;
      case 'Strong': return Colors.teal;
      case 'Fair': return Colors.orange;
      case 'Weak': return Colors.red;
      default: return Colors.red;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Password Strength Scanner'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: SingleChildScrollView(
          child: Column(
            children: [
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      const Icon(Icons.lock, size: 48, color: Colors.blue),
                      const SizedBox(height: 16),
                      const Text(
                        'Check Password Strength',
                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 8),
                      const Text(
                        'Enter your password to check its strength and security',
                        textAlign: TextAlign.center,
                        style: TextStyle(color: Colors.grey),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 16),
              TextField(
                controller: _passwordController,
                obscureText: !_isPasswordVisible,
                decoration: InputDecoration(
                  labelText: 'Password',
                  border: const OutlineInputBorder(),
                  prefixIcon: const Icon(Icons.lock),
                  suffixIcon: IconButton(
                    icon: Icon(_isPasswordVisible ? Icons.visibility_off : Icons.visibility),
                    onPressed: () => setState(() => _isPasswordVisible = !_isPasswordVisible),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isScanning ? null : _scanPassword,
                  child: _isScanning ? const CircularProgressIndicator() : const Text('Scan Password'),
                ),
              ),
              if (_result != null) ...[
                const SizedBox(height: 24),
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      children: [
                        const Text('Password Strength Analysis', style: TextStyle(fontWeight: FontWeight.bold)),
                        const SizedBox(height: 16),
                        Container(
                          width: 100,
                          height: 100,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            border: Border.all(color: _getStrengthColor(_result!['strength']), width: 4),
                          ),
                          child: Center(
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Text(
                                  '${_result!['strengthScore']}%',
                                  style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                                ),
                                Text(
                                  _result!['strength'],
                                  style: TextStyle(fontSize: 12, color: _getStrengthColor(_result!['strength'])),
                                ),
                              ],
                            ),
                          ),
                        ),
                        const SizedBox(height: 16),
                        _buildCriteriaRow('Length >= 8', _result!['length'] >= 8),
                        _buildCriteriaRow('Length >= 12', _result!['length'] >= 12),
                        _buildCriteriaRow('Uppercase letters', _result!['hasUppercase']),
                        _buildCriteriaRow('Lowercase letters', _result!['hasLowercase']),
                        _buildCriteriaRow('Numbers', _result!['hasNumbers']),
                        _buildCriteriaRow('Special characters', _result!['hasSpecial']),
                        const SizedBox(height: 8),
                        _buildCriteriaRow(
                          'Password leaked in data breach',
                          !_result!['isLeaked'],
                          invert: true,
                        ),
                        const SizedBox(height: 16),
                        if (_result!['isLeaked'])
                          const Text(
                            '⚠️ This password has been found in data breaches. Please choose a different password.',
                            style: TextStyle(color: Colors.red),
                            textAlign: TextAlign.center,
                          ),
                        if (_result!['isStrong'])
                          const Text(
                            '✓ This is a strong password!',
                            style: TextStyle(color: Colors.green),
                          ),
                      ],
                    ),
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCriteriaRow(String text, bool isMet, {bool invert = false}) {
    final isGood = invert ? !isMet : isMet;
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Icon(
            isGood ? Icons.check_circle : Icons.cancel,
            color: isGood ? Colors.green : Colors.red,
            size: 20,
          ),
          const SizedBox(width: 8),
          Expanded(child: Text(text)),
        ],
      ),
    );
  }
}