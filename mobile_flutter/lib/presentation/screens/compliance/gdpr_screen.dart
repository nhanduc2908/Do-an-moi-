import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/compliance_provider.dart';

class GdprScreen extends ConsumerStatefulWidget {
  const GdprScreen({super.key});

  @override
  ConsumerState<GdprScreen> createState() => _GdprScreenState();
}

class _GdprScreenState extends ConsumerState<GdprScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(complianceProvider.notifier).loadGdprStatus();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('GDPR Compliance'),
      ),
      body: ListView(
        children: [
          _buildArticle('Article 5 - Principles', 'Compliant', Colors.green),
          _buildArticle('Article 6 - Lawfulness of Processing', 'Compliant', Colors.green),
          _buildArticle('Article 7 - Conditions for Consent', 'Partial', Colors.orange),
          _buildArticle('Article 15 - Right of Access', 'Compliant', Colors.green),
          _buildArticle('Article 17 - Right to Erasure', 'Partial', Colors.orange),
          _buildArticle('Article 30 - Records of Processing', 'Non-Compliant', Colors.red),
          _buildArticle('Article 32 - Security of Processing', 'Compliant', Colors.green),
          _buildArticle('Article 33 - Breach Notification', 'Compliant', Colors.green),
        ],
      ),
    );
  }

  Widget _buildArticle(String title, String status, Color color) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: color.withOpacity(0.2),
          child: Icon(
            status == 'Compliant' ? Icons.check : (status == 'Partial' ? Icons.warning : Icons.close),
            color: color,
          ),
        ),
        title: Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
        trailing: Container(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
          decoration: BoxDecoration(
            color: color.withOpacity(0.2),
            borderRadius: BorderRadius.circular(20),
          ),
          child: Text(status, style: TextStyle(color: color, fontWeight: FontWeight.bold)),
        ),
        onTap: () {},
      ),
    );
  }
}