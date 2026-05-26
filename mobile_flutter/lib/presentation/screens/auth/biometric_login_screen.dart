import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/routes/route_names.dart';
import '../../../services/auth/biometric_auth.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';

class BiometricLoginScreen extends ConsumerStatefulWidget {
  const BiometricLoginScreen({super.key});

  @override
  ConsumerState<BiometricLoginScreen> createState() => _BiometricLoginScreenState();
}

class _BiometricLoginScreenState extends ConsumerState<BiometricLoginScreen> {
  bool _isLoading = false;
  bool _isAvailable = false;

  @override
  void initState() {
    super.initState();
    _checkBiometricAvailability();
  }

  Future<void> _checkBiometricAvailability() async {
    _isAvailable = await BiometricAuth.isAvailable();
    if (_isAvailable) {
      _authenticate();
    }
    setState(() {});
  }

  Future<void> _authenticate() async {
    setState(() => _isLoading = true);
    
    final success = await BiometricAuth.authenticate(
      reason: 'Authenticate to login to your account',
    );
    
    if (success && mounted) {
      // Auto login with stored credentials
      context.go(RouteNames.home);
    }
    
    setState(() => _isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.fingerprint, size: 100, color: Colors.blue),
            const SizedBox(height: 20),
            Text(
              'Biometric Login',
              style: Theme.of(context).textTheme.headlineMedium,
            ),
            const SizedBox(height: 10),
            Text(
              _isAvailable 
                ? 'Place your finger on the sensor to login'
                : 'Biometric authentication is not available on this device',
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.grey[600]),
            ),
            const SizedBox(height: 40),
            if (_isAvailable)
              CustomButton(
                text: _isLoading ? 'Authenticating...' : 'Use Fingerprint',
                onPressed: _authenticate,
                isLoading: _isLoading,
              ),
            const SizedBox(height: 16),
            TextButton(
              onPressed: () => context.go(RouteNames.login),
              child: const Text('Use Password Instead'),
            ),
          ],
        ),
      ),
    );
  }
}