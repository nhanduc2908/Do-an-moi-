import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../data/models/key_model.dart';
import '../../widgets/common/custom_button.dart';

class KeyListScreen extends ConsumerStatefulWidget {
  const KeyListScreen({super.key});

  @override
  ConsumerState<KeyListScreen> createState() => _KeyListScreenState();
}

class _KeyListScreenState extends ConsumerState<KeyListScreen> {
  final List<KeyModel> _keys = [
    KeyModel(keyId: 'KEY-001', type: 'AES', size: 256, status: 'active', fingerprint: 'AB:CD:EF:12:34'),
    KeyModel(keyId: 'KEY-002', type: 'RSA', size: 2048, status: 'active', fingerprint: '56:78:90:AB:CD'),
    KeyModel(keyId: 'KEY-003', type: 'ECC', size: 256, status: 'expired', fingerprint: 'EF:12:34:56:78'),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Encryption Keys'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _keys.length,
        itemBuilder: (context, index) {
          final key = _keys[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: key.status == 'active' ? Colors.green : Colors.red,
                child: const Icon(Icons.vpn_key, color: Colors.white),
              ),
              title: Text(key.keyId ?? 'Unknown', style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${key.type} ${key.size} bits • ${key.status}'),
              trailing: const Icon(Icons.chevron_right),
              onTap: () {},
            ),
          );
        },
      ),
    );
  }
}