import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class KeyRequestScreen extends ConsumerStatefulWidget {
  const KeyRequestScreen({super.key});

  @override
  ConsumerState<KeyRequestScreen> createState() => _KeyRequestScreenState();
}

class _KeyRequestScreenState extends ConsumerState<KeyRequestScreen> {
  final TextEditingController _reasonController = TextEditingController();
  String _keyType = 'AES';
  int _keySize = 256;
  bool _isSubmitting = false;

  Future<void> _submitRequest() async {
    if (_reasonController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please provide a reason for key request')),
      );
      return;
    }

    setState(() => _isSubmitting = true);
    await Future.delayed(const Duration(seconds: 1));
    setState(() => _isSubmitting = false);

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Key request submitted successfully')),
    );
    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Request New Key'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _keyType,
              items: const [
                DropdownMenuItem(value: 'AES', child: Text('AES - Symmetric Encryption')),
                DropdownMenuItem(value: 'RSA', child: Text('RSA - Asymmetric Encryption')),
                DropdownMenuItem(value: 'ECC', child: Text('ECC - Elliptic Curve')),
              ],
              onChanged: (value) => setState(() => _keyType = value!),
              decoration: const InputDecoration(
                labelText: 'Key Type',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<int>(
              value: _keySize,
              items: const [
                DropdownMenuItem(value: 128, child: Text('128 bits')),
                DropdownMenuItem(value: 256, child: Text('256 bits')),
                DropdownMenuItem(value: 2048, child: Text('2048 bits')),
                DropdownMenuItem(value: 4096, child: Text('4096 bits')),
              ],
              onChanged: (value) => setState(() => _keySize = value!),
              decoration: const InputDecoration(
                labelText: 'Key Size',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),
            CustomTextField(
              controller: _reasonController,
              label: 'Reason for Request',
              prefixIcon: Icons.description,
              maxLines: 4,
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Submit Request',
              onPressed: _submitRequest,
              isLoading: _isSubmitting,
            ),
          ],
        ),
      ),
    );
  }
}