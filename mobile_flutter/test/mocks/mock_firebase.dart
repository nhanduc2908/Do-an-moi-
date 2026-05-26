// Đường dẫn: mobile_flutter/test/mocks/mock_firebase.dart

import 'package:mockito/annotations.dart';
import 'package:mockito/mockito.dart';
import 'package:firebase_auth/firebase_auth.dart';
import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:firebase_storage/firebase_storage.dart';
import 'package:firebase_messaging/firebase_messaging.dart';

@GenerateMocks([
  FirebaseAuth,
  FirebaseFirestore,
  FirebaseStorage,
  FirebaseMessaging,
  User,
  UserCredential,
])

class MockFirebase {
  // Mock FirebaseAuth
  static MockFirebaseAuth createMockFirebaseAuth() {
    final mock = MockFirebaseAuth();
    final mockUser = createMockUser();
    
    when(mock.currentUser).thenReturn(mockUser);
    when(mock.authStateChanges()).thenAnswer((_) => Stream.value(mockUser));
    when(mock.signInWithEmailAndPassword(
      email: anyNamed('email'),
      password: anyNamed('password'),
    )).thenAnswer((_) async => createMockUserCredential());
    
    when(mock.createUserWithEmailAndPassword(
      email: anyNamed('email'),
      password: anyNamed('password'),
    )).thenAnswer((_) async => createMockUserCredential());
    
    when(mock.signOut()).thenAnswer((_) async => {});
    when(mock.sendPasswordResetEmail(email: anyNamed('email')))
        .thenAnswer((_) async => {});
    
    return mock;
  }

  static MockFirebaseAuth createMockFirebaseAuthWithError() {
    final mock = MockFirebaseAuth();
    
    when(mock.signInWithEmailAndPassword(
      email: anyNamed('email'),
      password: anyNamed('password'),
    )).thenThrow(FirebaseAuthException(
      code: 'user-not-found',
      message: 'User not found',
    ));
    
    return mock;
  }

  // Mock User
  static MockUser createMockUser() {
    final mock = MockUser();
    
    when(mock.uid).thenReturn('test_uid_123');
    when(mock.email).thenReturn('test@example.com');
    when(mock.displayName).thenReturn('Test User');
    when(mock.emailVerified).thenReturn(true);
    when(mock.phoneNumber).thenReturn('+1234567890');
    when(mock.metadata).thenReturn(createMockUserMetadata());
    
    return mock;
  }

  static MockUserMetadata createMockUserMetadata() {
    final mock = MockUserMetadata();
    when(mock.creationTime).thenReturn(DateTime.now());
    when(mock.lastSignInTime).thenReturn(DateTime.now());
    return mock;
  }

  // Mock UserCredential
  static MockUserCredential createMockUserCredential() {
    final mock = MockUserCredential();
    when(mock.user).thenReturn(createMockUser());
    return mock;
  }

  // Mock FirebaseFirestore
  static MockFirebaseFirestore createMockFirebaseFirestore() {
    final mock = MockFirebaseFirestore();
    final mockCollection = createMockCollectionReference();
    
    when(mock.collection(any)).thenReturn(mockCollection);
    
    return mock;
  }

  static MockCollectionReference<Map<String, dynamic>> createMockCollectionReference() {
    final mock = MockCollectionReference();
    final mockDocument = createMockDocumentReference();
    
    when(mock.doc(any)).thenReturn(mockDocument);
    when(mock.add(any)).thenAnswer((_) async => createMockDocumentReference());
    
    return mock;
  }

  static MockDocumentReference<Map<String, dynamic>> createMockDocumentReference() {
    final mock = MockDocumentReference();
    final mockSnapshot = createMockDocumentSnapshot();
    
    when(mock.id).thenReturn('test_doc_id');
    when(mock.get()).thenAnswer((_) async => mockSnapshot);
    when(mock.set(any)).thenAnswer((_) async => {});
    when(mock.update(any)).thenAnswer((_) async => {});
    when(mock.delete()).thenAnswer((_) async => {});
    
    return mock;
  }

  static MockDocumentSnapshot<Map<String, dynamic>> createMockDocumentSnapshot() {
    final mock = MockDocumentSnapshot();
    
    when(mock.exists).thenReturn(true);
    when(mock.id).thenReturn('test_doc_id');
    when(mock.data()).thenReturn({'key': 'value'});
    when(mock.get(any)).thenReturn('value');
    
    return mock;
  }

  // Mock QuerySnapshot
  static MockQuerySnapshot<Map<String, dynamic>> createMockQuerySnapshot() {
    final mock = MockQuerySnapshot();
    final mockDocuments = [createMockQueryDocumentSnapshot()];
    
    when(mock.docs).thenReturn(mockDocuments);
    when(mock.size).thenReturn(1);
    
    return mock;
  }

  static MockQueryDocumentSnapshot<Map<String, dynamic>> createMockQueryDocumentSnapshot() {
    final mock = MockQueryDocumentSnapshot();
    
    when(mock.id).thenReturn('test_doc_id');
    when(mock.data()).thenReturn({'key': 'value'});
    when(mock.get(any)).thenReturn('value');
    
    return mock;
  }

  // Mock FirebaseStorage
  static MockFirebaseStorage createMockFirebaseStorage() {
    final mock = MockFirebaseStorage();
    final mockReference = createMockStorageReference();
    
    when(mock.ref()).thenReturn(mockReference);
    when(mock.ref(any)).thenReturn(mockReference);
    
    return mock;
  }

  static MockReference createMockStorageReference() {
    final mock = MockReference();
    final mockTask = createMockStorageUploadTask();
    
    when(mock.putFile(any)).thenAnswer((_) => mockTask);
    when(mock.putData(any)).thenAnswer((_) => mockTask);
    when(mock.getDownloadUrl()).thenAnswer((_) async => Uri.parse('https://example.com/file.jpg'));
    when(mock.delete()).thenAnswer((_) async => {});
    
    return mock;
  }

  static MockUploadTask createMockStorageUploadTask() {
    final mock = MockUploadTask();
    
    when(mock.snapshot).thenReturn(createMockUploadTaskSnapshot());
    when(mock.whenComplete(any)).thenAnswer((invocation) {
      return Future.value(null);
    });
    
    return mock;
  }

  static MockUploadTaskSnapshot createMockUploadTaskSnapshot() {
    final mock = MockUploadTaskSnapshot();
    
    when(mock.bytesTransferred).thenReturn(1024);
    when(mock.totalBytes).thenReturn(10240);
    when(mock.state).thenReturn(TaskState.success);
    
    return mock;
  }

  // Mock FirebaseMessaging
  static MockFirebaseMessaging createMockFirebaseMessaging() {
    final mock = MockFirebaseMessaging();
    
    when(mock.getToken()).thenAnswer((_) async => 'test_fcm_token');
    when(mock.requestPermission()).thenAnswer((_) async => true);
    when(mock.subscribeToTopic(any)).thenAnswer((_) async => {});
    when(mock.unsubscribeFromTopic(any)).thenAnswer((_) async => {});
    
    return mock;
  }

  // Mock CollectionReference
  static MockCollectionReference<Map<String, dynamic>> createMockCollection() {
    final mock = MockCollectionReference();
    final mockQuery = createMockQuery();
    
    when(mock.where(any, isEqualTo: anyNamed('isEqualTo')))
        .thenAnswer((_) => mockQuery);
    when(mock.orderBy(any)).thenAnswer((_) => mockQuery);
    when(mock.limit(any)).thenAnswer((_) => mockQuery);
    when(mock.get()).thenAnswer((_) async => createMockQuerySnapshot());
    
    return mock;
  }

  static MockQuery<Map<String, dynamic>> createMockQuery() {
    final mock = MockQuery();
    
    when(mock.where(any, isEqualTo: anyNamed('isEqualTo')))
        .thenAnswer((_) => mock);
    when(mock.orderBy(any)).thenAnswer((_) => mock);
    when(mock.limit(any)).thenAnswer((_) => mock);
    when(mock.get()).thenAnswer((_) async => createMockQuerySnapshot());
    
    return mock;
  }
}

// Extension for creating test data
extension MockFirebaseData on MockFirebase {
  static Map<String, dynamic> createTestUserData() {
    return {
      'id': '1',
      'name': 'Test User',
      'email': 'test@example.com',
      'role': 'viewer',
      'status': 'active',
      'created_at': FieldValue.serverTimestamp(),
    };
  }

  static Map<String, dynamic> createTestAssessmentData() {
    return {
      'id': '1',
      'title': 'Security Assessment',
      'score': 85.5,
      'status': 'completed',
      'created_at': FieldValue.serverTimestamp(),
    };
  }

  static Map<String, dynamic> createTestIncidentData() {
    return {
      'id': '1',
      'code': 'INC-001',
      'title': 'Security Breach',
      'severity': 'high',
      'status': 'open',
      'created_at': FieldValue.serverTimestamp(),
    };
  }
}

// Mock classes for Firebase
class MockUserMetadata extends Mock implements UserMetadata {}
class MockCollectionReference<T> extends Mock implements CollectionReference<T> {}
class MockDocumentReference<T> extends Mock implements DocumentReference<T> {}
class MockDocumentSnapshot<T> extends Mock implements DocumentSnapshot<T> {}
class MockQuerySnapshot<T> extends Mock implements QuerySnapshot<T> {}
class MockQueryDocumentSnapshot<T> extends Mock implements QueryDocumentSnapshot<T> {}
class MockQuery<T> extends Mock implements Query<T> {}
class MockReference extends Mock implements Reference {}
class MockUploadTask extends Mock implements UploadTask {}
class MockUploadTaskSnapshot extends Mock implements UploadTaskSnapshot {}