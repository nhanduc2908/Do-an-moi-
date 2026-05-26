import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/compliance_provider.dart';

class Iso27001Screen extends ConsumerStatefulWidget {
  const Iso27001Screen({super.key});

  @override
  ConsumerState<Iso27001Screen> createState() => _Iso27001ScreenState();
}

class _Iso27001ScreenState extends ConsumerState<Iso27001Screen> {
  @override
  void initState() {
    super.initState();
    ref.read(complianceProvider.notifier).loadIso27001Status();
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(complianceProvider);
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('ISO 27001 Compliance'),
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              children: [
                _buildSection('A.5 - Information Security Policies', 'Implemented', 100),
                _buildSection('A.6 - Organization of Information Security', 'Implemented', 100),
                _buildSection('A.7 - Human Resource Security', 'Partial', 60),
                _buildSection('A.8 - Asset Management', 'Implemented', 95),
                _buildSection('A.9 - Access Control', 'Partial', 70),
                _buildSection('A.10 - Cryptography', 'Not Started', 0),
                _buildSection('A.11 - Physical Security', 'Implemented', 90),
                _buildSection('A.12 - Operations Security', 'Partial', 65),
              ],
            ),
    );
  }

  Widget _buildSection(String title, String status, int progress) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: ExpansionTile(
        title: Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Text('Status: $status • Progress: $progress%'),
        trailing: Container(
          width: 80,
          height: 4,
          child: LinearProgressIndicator(
            value: progress / 100,
            backgroundColor: Colors.grey.shade200,
          ),
        ),
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Controls:', style: TextStyle(fontWeight: FontWeight.bold)),
                const Text('• Control 1 description'),
                const Text('• Control 2 description'),
                const SizedBox(height: 12),
                ElevatedButton(
                  onPressed: () {},
                  child: const Text('View Details'),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}