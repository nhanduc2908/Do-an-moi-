import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../data/models/key_model.dart';
import '../../widgets/common/custom_button.dart';

class KeyDetailScreen extends ConsumerStatefulWidget {
  const KeyDetailScreen({super.key, required this.keyId});
  final String keyId;

  @override
  ConsumerState<KeyDetailScreen> createState() => _KeyDetailScreenState();
}

class _KeyDetailScreenState extends ConsumerState<KeyDetailScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Key Details'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildDetailRow('Key ID', widget.keyId),
                    _buildDetailRow('Type', 'AES'),
                    _buildDetailRow('Size', '256 bits'),
                    _buildDetailRow('Status', 'Active'),
                    _buildDetailRow('Created', '2024-01-15'),
                    _buildDetailRow('Expires', '2025-01-15'),
                    _buildDetailRow('Fingerprint', 'AB:CD:EF:12:34:56:78:90'),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: CustomButton(
                    text: 'Revoke',
                    onPressed: () {},
                    isOutlined: true,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: CustomButton(
                    text: 'Rotate',
                    onPressed: () {},
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          SizedBox(width: 100, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey))),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }
}